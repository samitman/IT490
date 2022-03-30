#!/usr/bin/env python3
#This file is for testing purposes on RMQ server
import pika

connection = pika.BlockingConnection(
    pika.ConnectionParameters(host='localhost'))

channel = connection.channel()

channel.queue_declare(queue='login')
 

def on_request(ch, method, props, body):

    ch.basic_publish(exchange='', routing_key='login',
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body="Hello from RMQ")
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='login', on_message_callback=on_request)

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()
