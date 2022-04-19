#!/usr/bin/env python3
import pika
import uuid
import sys, os

username = sys.argv[1]
withdrawAmount = sys.argv[2]
withdrawInfo = str(username+","+withdrawAmount)

class withdrawClient(object):

    def __init__(self):
        credentials = pika.PlainCredentials("test", "test")
        self.connection = pika.BlockingConnection(
            pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

        self.channel = self.connection.channel()

        result = self.channel.queue_declare(queue='', exclusive=True)
        self.callback_queue = result.method.queue

        self.channel.basic_consume(queue=self.callback_queue, consumer_callback=self.on_response)

    def on_response(self, ch, method, props, body):
        if self.corr_id == props.correlation_id:
            self.response = body

    def call(self, userinfo):
        self.response = None
        self.corr_id = str(uuid.uuid4())
        self.channel.basic_publish(
            exchange='',
            routing_key='withdraw_fe_be',
            properties=pika.BasicProperties(
                reply_to=self.callback_queue,
                correlation_id=self.corr_id,
            ),
            body= userinfo)
        while self.response is None:
            self.connection.process_data_events()
        return self.response


userDeposit = withdrawClient()

response = userDeposit.call(withdrawInfo) #withdraw info is username,withdrawAmount
Fe_response = response.decode()
if Fe_response == "1": print("1")
else: print("0")
