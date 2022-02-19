#!/usr/bin/env python
import pika, sys, os

#declare connection
#host = sam's laptop
def main():
    credentials = pika.PlainCredentials(username='test', password='test')
    connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.68.192.60', credentials=credentials))
    channel = connection.channel()

#declare queue
    channel.queue_declare(queue='hello')

    def callback(ch, method, properties, body):
        print(" [x] Received %r" % body)

#callback function
    channel.basic_consume(callback, queue='hello', no_ack=True)

    print(' [*] Waiting for messages. To exit press CTRL+C')
    channel.start_consuming()

#invoke main
if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print('Interrupted')
        try:
            sys.exit(0)
        except SystemExit:
            os._exit(0)