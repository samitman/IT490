import requests
import pika
import yfinance as yf

stocks = ['AAPL', 'MSFT', 'AMD']

for stock in stocks:
    info = yf.Ticker(stock).info
    marketprice = str(info.get('regularMarketPrice'))
    print(stock,marketprice)
    message=stock +","+ marketprice
    print('Debug'+message)

    credentials = pika.PlainCredentials(username='test', password='test')
    connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

    channel = connection.channel()

    channel.queue_declare(queue='stock')

    channel.basic_publish(exchange='',
                    routing_key='stock',
                    body= message)
    print('Sent', message)
    connection.close()