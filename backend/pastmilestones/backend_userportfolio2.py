#!/usr/bin/env python3
import pika
import uuid
import sys


#def main(username,portfolioData):
#    portInfo = str(username)+','+str(portfolioData)

def main(username,balance,etfMeme,etf,etfBoomer,etfTech,etfCrypto,etfModerate,etfAggressive,etfGrowth):
    portInfo = str(username)+','+str(balance)+','+str(etfMeme)+','+str(etfBoomer)+','+str(etfTech)+','+str(etfCrypto)+','+str(etfModerate)+','+str(etfAggressive)+','+str(etfGrowth)         

    class portfolioClient(object):

        def __init__(self):
            credentials = pika.PlainCredentials(username='test', password='test')
            self.connection = pika.BlockingConnection(
                pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

            self.channel = self.connection.channel()

            result = self.channel.queue_declare(queue='', exclusive=True)
            self.callback_queue = result.method.queue

            self.channel.basic_consume(queue=self.callback_queue, consumer_callback=self.on_response)

        def on_response(self, ch, method, props, body):
            if self.corr_id == props.correlation_id:
                self.response = body

        def call(self, portInfo):
            self.response = None
            self.corr_id = str(uuid.uuid4())
            self.channel.basic_publish(
                exchange='',
                routing_key='userportfolio_be_db',
                properties=pika.BasicProperties(
                    reply_to=self.callback_queue,
                    correlation_id=self.corr_id,
                ),
                body= portInfo)
            while self.response is None:
                self.connection.process_data_events()
            return self.response


    userportdata = portfolioClient()
    response = userportdata.call(portInfo)
    return(response)