#!/usr/bin/env python3

import pika, sys, os, mysql.connector

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='deposit_be_db')
 

def dbinsertion(depositDict):
    mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)
    msg = ''
    print(depositDict)
    cursor = mydb.cursor()
    select_stmt=('SELECT * FROM accounts WHERE Username = %(Username)s')
    cursor.execute(select_stmt, depositDict)
    account = cursor.fetchone()
    if account:
        print("Account found...")
        insert_stmt=('UPDATE accounts SET Balance = Balance+%(Deposit)s WHERE Username = %(Username)s')
        cursor.execute(insert_stmt, depositDict)
        print("Deposit made")
        mydb.commit()
        msg = '1'
        return msg
    else:
        print("Deposit failed")
        msg = '0'
        return msg

def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    messagestring = body.decode()
    depositList = messagestring.split(',')
    username = depositList[0]
    depositAmount = depositList[1]  
    print("Split check:" + username +" "+ depositAmount)
    print(depositList)
    depositDict =  {"Username": username,"Deposit": depositAmount }

    
    response = dbinsertion(depositDict)

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=str(response))
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='deposit_be_db')

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()
