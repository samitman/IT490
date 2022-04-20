#!/usr/bin/env python3

import pika, sys, os, uuid
from backend_login2 import main


credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='rpc_log_fe_be') #FROM FE to BE
 
def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    messagestring = body.decode()
    credslist = messagestring.split(',')
    uid = credslist[0]
    email = credslist[1]
    username = credslist[2]
    firstName = credslist[3]
    lastName = credslist[4]
    balance = credslist[5]
    etfMeme = credslist[6]
    etfBoomer = credslist[7]
    etfTech = credslist[8]
    etfCrypto = credslist[9]
    etfModerate = credslist[10]
    etfAggressive = credslist[11]
    etfGrowth = credslist[12]

    print("Split check:" + uid +" "+ email +" "+ username +" "+ firstName +" "+ lastName +" "+ balance +" "+ etfMeme +" "+ etfTech +" "+ etfCrypto +" "+ etfModerate +" "+ etfAggressive+" "+ etfGrowth)
    print(credslist)
    credsdict =  {"UserID": uid,"Email": email,"Username": username,"firstName": firstName,"lastName": lastName, "balance": balance,"etfMeme": etfMeme,"etfBoomer": etfBoomer,"etfTech": etfTech,"etfCrypto": etfCrypto,"etfModerate": etfModerate ,"etfAggressive": etfAggressive,"etfGrowth": etfGrowth }

    
    #call "be_reg2.py username password
    response = main(uid,email,username,firstName,lastName,balance,etfMeme,etfBoomer,etfTech,etfCrypto,etfModerate,etfAggressive,etfGrowth) #FROM BE TO DB
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
channel.basic_consume(consumer_callback=on_request, queue='rpc_log_fe_be') #FROM BE TO FE RESPONSE

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()