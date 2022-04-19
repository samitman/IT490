#!/usr/bin/env python3

import pika, sys, os, uuid
from backend_withdraw2 import main


credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='withdraw_fe_be') #FROM FE to BE
 
def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)

    messagestring = body.decode()
    withdrawInfo = messagestring.split(',')
    username = withdrawInfo[0]
    withdrawAmount = withdrawInfo[1]  
    print(withdrawInfo)

    credsdict =  {"Username": username,"Withdraw": withdrawAmount }

    
    #call "be_withdraw2.py username depositAmount
    response = main(username,withdrawAmount) #FROM BE TO DB

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=response)
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='withdraw_fe_be') #FROM BE TO FE RESPONSE

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()