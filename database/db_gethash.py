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

channel.queue_declare(queue='rpc_hash_be_db')
 


def dbinsertion(dict):
    mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)
    msg = ''
    print(dict)
    cursor = mydb.cursor()
    select_stmt=('SELECT Password FROM accounts WHERE Username = %(Username)s')
    cursor.execute(select_stmt, dict)
    hash = cursor.fetchone()
    if hash:
        print("Account found!, Getting hashpass..")
        hashpass = hash[1]
        msg = str(hashpass)
        return msg
    else:
        print("Account does not exist")
        msg = '0'
        return msg

def on_request(ch, method, props, body):
    print(" [x] Received %r" % body)
    print(type(body))

    username = body.decode()
    dict =  {"Username": username }

    
    response = dbinsertion(dict)

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=str(response))
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(consumer_callback=on_request, queue='rpc_price_be_db')

print(" [x] Awaiting RPC requests")
print({on_request})
channel.start_consuming()
