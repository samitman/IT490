#!/usr/bin/env python
import pika

#create connection to rabbitmq
connection = pika.BlockingConnection(
    pika.ConnectionParameters(hosts='localhost'))
channel = connection.channel()

#checks if queue exist
channel.queue_declare(queue='hello')

channel.basic_publish(exchange='', routing_key='hello', body='Hello World!')
print(" [x] Send ' Hello World!'")
#closes connection
connection.close()