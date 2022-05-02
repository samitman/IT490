#!/usr/bin/env python3

import pika, sys, os, uuid
from backend_gethash import main as getHash
from hash_log2 import main

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='rpc_fe_be') #FROM FE to BE
 
def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    messagestring = body.decode()
    credslist = messagestring.split(',')
    username = credslist[0]
    password = credslist[1]  
    
    
    def checkhash(password,storedhashpass):
        return bcrypt.checkpw(password, storedhashpass)
    
    storedhash = getHash(username)
    hashstring = storedhash.decode()
    answer = checkhash(password, hashstring)
    if answer == True:
        response = main(username)
    else:
        response = '0'


    ch.basic_publish(exchange='',
                    routing_key=props.reply_to,
                    properties=pika.BasicProperties(correlation_id = \
                                                        props.correlation_id),
                    body=response)
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='rpc_fe_be') #FROM BE TO FE RESPONSE

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()