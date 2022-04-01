#!/usr/bin/env python3
import pika, os

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.192.61', credentials=credentials))
channel = connection.channel()

channel.queue_declare(queue='hello')

channel.basic_publish(exchange='',
                      routing_key='hello',
                      body='Hello from fe_cris!')
print(" [x] Sent 'Hello World!'")

connection.close()
