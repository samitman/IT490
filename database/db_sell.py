#!/usr/bin/env python3

import pika, sys, os, mysql.connector

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='sell_be_db')
 

def dbinsertion(investDict):
    mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)
    msg = ''
    print(investDict)
    cursor = mydb.cursor()
    select_stmt=('SELECT * FROM accounts WHERE Username = %(Username)s')
    cursor.execute(select_stmt, investDict)
    account = cursor.fetchone()
    if account:
        print("Account found...")
        #inserting new balance into table
        insert_stmt=('UPDATE accounts SET Balance = Balance+%(Deposit)s WHERE Username = %(Username)s')
        cursor.execute(insert_stmt, investDict)
        print("Balance adjusted")
        mydb.commit()

        #inserting shares into table
        insert_stmt=('UPDATE accounts SET {field} = {field}-%(Shares)s WHERE Username = %(Username)s').format(field=investDict["etfName"])
        cursor.execute(insert_stmt, investDict)
        mydb.commit()
        print("Shares allocated to user account!")

        
        #constructing final message to be sent to FE
        select_stmt=('SELECT Balance FROM accounts WHERE Username = %(Username)s')
        cursor.execute(select_stmt, investDict)
        account = cursor.fetchone()
        newBalance = str(account[0])
        
        #grabbing price
        select_stmt=('SELECT Price FROM stocks WHERE Ticker = %(etfName)s')
        cursor.execute(select_stmt, investDict)
        price = cursor.fetchone()
        newPrice = str(price[0])

        
        msg = str(investDict["Username"]+","+investDict["Shares"]+","+newPrice+","+newBalance)
        return msg
    else:
        print("Investment failed, username not found")
        msg = '0'
        return msg

def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    messagestring = body.decode()
    investList = messagestring.split(',')
    username = investList[0]
    etfName = investList[1]
    depositAmount = investList[2]
    sharesAmount = investList[3]  
    print("Split check:" + username +" "+etfName+" "+ depositAmount)
    print(investList)
    investDict =  {"Username": username, "etfName":etfName, "Deposit": depositAmount, "Shares": sharesAmount }

    
    response = dbinsertion(investDict)

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=str(response))
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='sell_be_db')

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()