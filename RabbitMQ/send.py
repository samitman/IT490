#!/usr/bin/env python3
import pika

connection = pika.BlockingConnection(pika.ConnectionParameters('localhost'))
channel = connection.channel()

channel.queue_declare(queue='hello')

channel.basic_publish(exchange='',
                      routing_key='hello',
                      body='Hello from Sam on RabbitMQ!')
print(" [x] Sent 'Hello From Sam on RabbitMQ!'")

connection.close()
