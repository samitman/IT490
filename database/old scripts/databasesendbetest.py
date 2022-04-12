#!/usr/bin/env python3
import pika, sys, os, mysql.connector

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters('192.168.192.61', credentials=credentials))
channel = connection.channel()
channel.queue_declare(queue='user')

mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)

mycursor = mydb.cursor()
mycursor.execute ("SELECT * FROM accounts")
myresult = mycursor.fetchone()
print(type(myresult))
print(myresult)

print(type(myresult[0]))
myresultstring = (myresult[0])



channel.basic_publish(exchange='',
                  routing_key='user',
                  body= myresultstring)
print(" [x] Sent username")
connection.close()




