#!/usr/bin/env python3
import pika
import datetime
e = datetime.datetime.now()

#uses rabbitmq site credentials in this case it is cris's
credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.61', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='test_quorum', durable=True, arguments={"x-queue-type":"quorum"})

channel.basic_publish(exchange='',
                  routing_key='test_quorum',
                  body= 'Hello from Cris Frontend!' + 'Current date and time = %s' % e)
print(" [x] sent 'hello from cris front end'")
print ("Current date and time = %s" % e)

connection.close()

