#!/usr/bin/env python3

import pika, sys, os, uuid, bcrypt
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

    def gethashpass(plainpassword):
        return bcrypt.hashpw(plainpassword, bcrypt.gensalt())

    hashedpassword = gethashpass(plainpassword)

    #checks hash / salt saved onto hash
    #def checkhash(password,hashpass):
    #    return bcrypt.checkpw(password, hashpass)

    #salt = uuid.uuid4().bytes
    #hashedpass = hashlib.sha512(password + salt).digest()

    #call "be_reg2.py username password email first and last name

    response = main(email,username,hashedpassword,firstName,lastName) #FROM BE TO DB
    #print("Output: " + str(response))
    #response = output of backend_registration2.py
    #response is the new queue between backend and db

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