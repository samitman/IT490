#!/usr/bin/env python
import pika

#uses rabbitmq site credentials in this case it is cris's
credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.61', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='hello')

channel.basic_publish(exchange='',
                  routing_key='hello',
                  body='Hello from Ryan!')
print(" [x] Sent 'Hello from Ryan!'")
connection.close()
