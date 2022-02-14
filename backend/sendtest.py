#!/usr/bin/env python
import pika

#uses rabbitmq site credentials in this case it is sam's
credentials = pika.PlainCredentials('sam', '1234')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='hello')

channel.basic_publish(exchange='',
                  routing_key='hello',
                  body='Hello World!')
print(" [x] Sent 'Hello RabbitMQ!'")
connection.close()