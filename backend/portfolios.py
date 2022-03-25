import requests
import pika
import yfinance as yf

portfolios = {'etfMeme': ['AMC', 'DOGE-USD', 'GME'], 'etfMedium': ['TSLA','NIO','NVDA']}

for category in portfolios:
    sum = 0
    for stock in portfolios[category]:
        info = yf.Ticker(stock).info
        marketprice = info.get('regularMarketPrice')
        sum += marketprice
        message= stock +","+ str(round(marketprice,2))
        print(message)
        message2= category +","+ str(round(sum,2))
        print(message2)
    #print (category + ": " + str(round(sum,2)))

    credentials = pika.PlainCredentials(username='test', password='test')
    connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

    channel = connection.channel()

    channel.queue_declare(queue='stock')

    channel.basic_publish(exchange='',
                    routing_key='stock',
                    body= message + message2)
    print('Sent', message, message2)
    connection.close()