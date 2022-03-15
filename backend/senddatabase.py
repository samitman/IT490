#!/usr/bin/env python3
import pika

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.61', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='registration')

channel.basic_publish(exchange='',
                  routing_key='registration',
                  body= input("Enter test username to enter on the database: "))
print(" [x] Sent username")
connection.close()