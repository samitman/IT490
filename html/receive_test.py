#!/usr/bin/env python3

import pika, sys, os

def main():
    credentials = pika.PlainCredentials(username='test', password='test')
    connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.192.60', credentials=credentials))
    channel = connection.channel()

    channel.queue_declare(queue='hello')

    def callback(ch, method, properties, body):
        print(" [x] Received %r" % body)

    channel.basic_consume(callback, queue='hello', no_ack=True)

    print(' [*] Waiting for messages. To exit press CTRL+C')
    channel.start_consuming()

if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print('Interrupted')
        try:
            sys.exit(0)
        except SystemExit:
            os._exit(0)
