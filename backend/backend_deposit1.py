#!/usr/bin/env python3

import pika, sys, os, uuid
from backend_deposit2 import main


credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='deposit_fe_be') #FROM FE to BE
 
def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)

    messagestring = body.decode()
    depositInfo = messagestring.split(',')
    username = depositInfo[0]
    depositAmount = depositInfo[1]  
    print(depositInfo)

    credsdict =  {"Username": username,"Deposit": depositAmount }

    
    #call "be_deposit2.py username depositAmount
    response = main(username,depositAmount) #FROM BE TO DB

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=response)
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='deposit_fe_be') #FROM BE TO FE RESPONSE

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()