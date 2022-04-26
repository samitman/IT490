#!/usr/bin/env python3

import pika, sys, os, uuid
from backend_registration2 import main

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='rpc_fe_be') #FROM FE to BE
 
def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    messagestring = body.decode()
    credslist = messagestring.split(',')
    print(credslist)    
    email = credslist[0]
    username = credslist[1]
    plainpassword = credslist[2]
    firstName = credslist[3]
    lastName = credslist[4]

    print("Split check:" + email +" "+ username +" "+ plainpassword +" "+ firstName +" "+ lastName)
    print(credslist)
    credsdict =  {"Email": email,"Username": username,"Password": plainpassword,"First Name": firstName,"Last Name": lastName }

    def checkhash(password,storedhashpass):
        return bcrypt.checkpw(password, storedhashpass)

    password = 'password123'
    storedhash = '$2a$12$izKi4jESEOHVIjGP4LmjK.Zqv/RiT9mnSxFgTj8Wj4./jwtn0PxDa'
    answer = checkhash(password, storedhash)
    print(answer)

    response = main(email,username,storedhash,firstName,lastName) #FROM BE TO DB

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