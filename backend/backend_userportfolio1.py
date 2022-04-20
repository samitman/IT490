#!/usr/bin/env python3

import pika, sys, os, uuid
from backend_userportfolio2 import main


credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

#channel.queue_declare(queue='rpc_fe_be') #FROM FE to BE
 
def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    messagestring = body.decode()
    credslist = messagestring.split(',')
    username = credslist[0]
    balance = credslist[1]
    etfMeme = credslist[2]
    etfBoomer = credslist[3]
    etfTech = credslist[4]
    etfCrypto = credslist[5]
    etfModerate = credslist[6]
    etfAggressive = credslist[7]
    etfGrowth = credslist[8]
      
    print("Split check:" + username +" "+ balance +" "+ etfMeme +" "+ etfBoomer +" "+ etfTech +" "+ etfCrypto +" "+ etfModerate +" "+ etfAggressive+" "+ etfGrowth)
    print(credslist)
    credsdict =  {"username": username,"balance": balance,"etfMeme": etfMeme,"etfBoomer": etfBoomer,"etfTech": etfTech,"etfCrypto": etfCrypto,"etfModerate": etfModerate ,"etfAggressive": etfAggressive,"etfGrowth": etfGrowth }

    
    #call "backend_userportfolio2.py all user owned portfolio
    response = main(username,balance,etfMeme,etfBoomer,etfTech,etfCrypto,etfModerate,etfAggressive,etfGrowth) #FROM BE TO DB
    #print("Output: " + str(response))
    #response = output of backend_userportfolio2.py
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
