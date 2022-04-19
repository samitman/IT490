#!/usr/bin/env python3

import pika, sys, os, mysql.connector

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='withdraw_be_db')
 

def dbinsertion(withdrawDict):
    mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)
    msg = ''
    print(withdrawDict)
    cursor = mydb.cursor()
    select_stmt=('SELECT * FROM accounts WHERE Username = %(Username)s')
    cursor.execute(select_stmt, withdrawDict)
    account = cursor.fetchone()
    if account:
        print("Account found...")
        insert_stmt=('UPDATE accounts SET Balance = Balance-%(Withdraw)s WHERE Username = %(Username)s')
        cursor.execute(insert_stmt, withdrawDict)
        print("Withdraw made")
        mydb.commit()
        msg = '1'
        return msg
    else:
        print("Withdraw failed")
        msg = '0'
        return msg

def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    messagestring = body.decode()
    withdrawList = messagestring.split(',')
    username = withdrawList[0]
    withdrawAmount = withdrawList[1]  
    print("Split check:" + username +" "+ withdrawAmount)
    print(withdrawList)
    withdrawDict =  {"Username": username,"Withdraw": withdrawAmount }

    
    response = dbinsertion(withdrawDict)

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=str(response))
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='withdraw_be_db')

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()