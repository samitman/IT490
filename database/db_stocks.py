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
    connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.192.61', credentials=credentials))
    channel = connection.channel()

    channel.queue_declare(queue='stock')

    def callback(ch, method, properties, body):
        print(" [x] Received %r" % body)
        print(type(body))

        stockstring = (body.decode())
        stockslist = stockstring.split(',')
        ticker = stockslist[0]
        price = stockslist[1]
        
        print("Split check:" + ticker +" "+ price)
        print(stockslist)

        stocksdict =  {"Tick": ticker,"price": price }

        
        ##lets you execute python as sql statements, cursor init
        mycursor = mydb.cursor()

        #If the username doesn't already exist as a key, it will execute the sql statement
        sql = "INSERT INTO stocks (Ticker, Price) VALUES (%(Tick)s, %(price)s) ON DUPLICATE KEY UPDATE Price = (%(price)s);"
        
        ##executes
        mycursor.execute(sql, stocksdict)
		

        #writes changes to DB
        mydb.commit()
        

    channel.basic_consume(callback, queue='stock', no_ack=True)

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
