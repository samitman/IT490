#!/usr/bin/env python3

import pika, sys, os, mysql.connector
##database connection
#mydb = mysql.connector.connect(
 # host="localhost",
  #user="test",
  #password="1234",
  #database='test'
#)

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='rpc_log_be_db')
 


def dbinsertion(credsdict):
    mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)
    msg = ''
    print(credsdict)
    cursor = mydb.cursor()
    select_stmt=('SELECT * FROM accounts WHERE Username = %(Username)s')
    cursor.execute(select_stmt, credsdict)
    account = cursor.fetchone()
    if account:
        print("Account found, logging in!")
        uid = str(account[0])
        email = str(account[1])
        username = str(account[2])
        first = str(account[4])
        last = str(account[5])
        balance = str(account[6])
        eftMeme = str(account[7])
        eftBoomer = str(account[8])
        eftTech = str(account[9])
        eftCrypto = str(account[10])
        eftModerate = str(account[11])
        eftAggressive = str(account[12])
        eftGrowth = str(account[13])
        

        msg = str(uid+","+email+","+username+","+first+","+last+","+balance+","+eftMeme+","+eftBoomer+","+eftTech+","+eftCrypto+","+eftModerate+","+eftAggressive+","+eftGrowth)
        return msg
    else:
        print("Account doesn't exist or username/password incorrect")
        msg = '0'
        return msg

def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    username = body.decode()
    credsdict =  {"Username": username}

    
    response = dbinsertion(credsdict)

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=str(response))
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='rpc_log_be_db')

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()
