#!/usr/bin/env python3
import pika

connection = pika.BlockingConnection(pika.ConnectionParameters('localhost'))
channel = connection.channel()

channel.queue_declare(queue='registration')

channel.basic_publish(exchange='',
                  routing_key='registration',
                  body= input("Enter test username to enter on the database: "))
print(" [x] Sent username")
connection.close()




