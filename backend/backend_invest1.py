#!/usr/bin/env python3

import pika, sys, os, uuid
from backend_invest2 import main
from backend_getprice import main as getPrice


credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='invest_fe_be') #FROM FE to BE
 
def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)

    messagestring = body.decode()
    investInfo = messagestring.split(',')

    username = investInfo[0]
    portfolio = investInfo[1]
    amount = investInfo[2]  #dollar amount
    print(investInfo)

    #get amount in terms of shares
    etfPrice = getPrice(portfolio)
    shares = amount/etfPrice


    #sam,etfMeme,shares

    
    #call "be_deposit2.py username depositAmount
    response = main(username,portfolio,amount,shares) #FROM BE TO DB

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=response)
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='invest_fe_be') #FROM BE TO FE RESPONSE

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()