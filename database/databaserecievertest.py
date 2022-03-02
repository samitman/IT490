#!/usr/bin/env python3

import pika, sys, os, mysql.connector


##database connection
mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)

def main():
    credentials = pika.PlainCredentials(username='test', password='test')
    connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.192.60', credentials=credentials))
    channel = connection.channel()

    channel.queue_declare(queue='registration')

    def callback(ch, method, properties, body):
        print(" [x] Received %r" % body)
        print(type(body))

        ##saving body of message as username variable
        messagestring = body.decode()
        credslist = messagestring.split(',')
        username = credslist[0]
        password = credslist[1]
        
        print("Split check:" + username +" "+ password)

        print(credslist)
        ##lets you execute python as sql statements, cursor init
        mycursor = mydb.cursor()

        #If the username doesn't already exist as a key, it will execute the sql statement
        sql = "INSERT INTO accounts (Username, Password) VALUES (%s, %s)"
        
        
        mycursor.execute(sql, credslist)
		
        ##executes
        ##mycursor.execute(sql, val)

        #writes changes to DB
        mydb.commit()
        

    channel.basic_consume(callback, queue='registration', no_ack=True)

    print(' [*] Waiting for messages. To exit press CTRL+C')
    channel.start_consuming()

if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print('Interrupted')
        try:
            sys.exit(0)
        except SystemExit:
            os._exit(0)
