#!/usr/bin/env python3

import pika, sys, os, mysql.connector
##database connection
mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='rpc_queue')
 

def dbinsertion(credsdict):
    msg = ''
    print(credsdict)
    cursor = mydb.cursor()
    select_stmt=('SELECT * FROM accounts WHERE Username = %(Username)s')
    cursor.execute(select_stmt, credsdict)
    account = cursor.fetchone()
    if account:
        print("Account already exists! Account creation failed!")
        msg = '0'
        return msg
    else:
    	insert_stmt=('INSERT INTO accounts (Username, Password) VALUES (%(Username)s, %(Password)s)')
    	cursor.execute(insert_stmt, credsdict)
    	print("Account successfully created")
    	mydb.commit()
    	msg = '1'
    	return msg



def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    messagestring = body.decode()
    credslist = messagestring.split(',')
    username = credslist[0]
    password = credslist[1]  
    print("Split check:" + username +" "+ password)
    print(credslist)
    credsdict =  {"Username": username,"Password": password }

    
    response = dbinsertion(credsdict)

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=str(response))
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='rpc_queue')

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()
