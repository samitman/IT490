#!/usr/bin/env python3
import pika
import uuid
import sys, os

fname = sys.argv[1]
lname = sys.argv[2]
email = sys.argv[3]
username = sys.argv[4]
passwordHash = sys.argv[5]

creds = str(fname+","+lname+","+email+","+username+","+passwordHash)

class RegistrationClient(object):

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
            routing_key='rpc_fe_be', #From FE to BE
            properties=pika.BasicProperties(
                reply_to=self.callback_queue,
                correlation_id=self.corr_id,
            ),
            body= userinfo)
        while self.response is None:
            self.connection.process_data_events()
        return self.response


userregistration = RegistrationClient()

print(" [x] Requesting to register a new user")
response = userregistration.call(creds)
Fe_response = response.decode()
print(Fe_response)
if Fe_response == "1": print("Account successfully created, please login.")
else: print("Username Taken")

#print(Fe_response)

