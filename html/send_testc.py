#!/usr/bin/env python3
import pika
import datetime

e = datetime.datetime.now()

connection = pika.BlockingConnection(pika.ConnectionParameters('localhost'))
channel = connection.channel()

channel.queue_declare(queue='test_quorum', durable=True, arguments={"x-queue-type":"quorum"})


channel.basic_publish(exchange='',
                      routing_key='test_quorum',
                      body='Hello from FE, testing out quorum, '+ "Current date and time = %s" % e )
print (" [x] Sent 'Hello from RMQ3'")
print ("Current date and time = %s" % e)
#print ("Today's date:  = %s/%s/%s" % (e.day, e.month, e.year))
#print ("The time is now: = %s:%s:%s" % (e.hour, e.minute, e.second))

connection.close()

