#!/usr/bin/env python3

import pika, sys, os

def main():

    #first, I receive a message from the hello queue

    connection = pika.BlockingConnection(pika.ConnectionParameters(host='localhost'))
    channel = connection.channel()

    channel.queue_declare(queue ='test_quorum', durable=True, arguments={"x-queue-type":"quorum"})

    def callback(ch, method, properties, body):
        print(" [x] Received %r" % body)

    channel.basic_consume(queue='test_quorum', on_message_callback=callback, auto_ack=True)

    print(' [*] Waiting for messages. To exit press CTRL+C')
    channel.start_consuming()

    #connection.close()

    #next, pass a message onto the hello1 queue 
    for method_frame, properties, body in channel.consume(queue):

            # break of the loop after 2 min of inactivity (no new item fetched)
            if method_frame:

                connection = pika.BlockingConnection(pika.ConnectionParameters(host='localhost'))

                channel = connection.channel()

                channel.queue_declare(queue ='test_quorum', durable=True, arguments={"x-queue-type":"quorum"})

                channel.basic_publish(exchange='', routing_key='test_quorum', body='Response message on Quorum Queue!')
                print("Sent response")

                connection.close()

    connection.close()


if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print('Interrupted')
        try:
            sys.exit(0)
        except SystemExit:
            os._exit(0)

