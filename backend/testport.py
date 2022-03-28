import requests
import pika
import yfinance as yf

portfolios = {'Meme': ['GME', 'AMC', 'DOGE-USD','DWAC','CLOV'],
              'Boomer': ['WMT','KO','JNJ','T','UNH'],
              'Tech': ['AMD','TSLA','MSFT','GOOGL','AAPL'],
              'Crypto': ['BTC-USD','ETH-USD','BNB-USD','XRP-USD','ADA-USD'],
              'Moderate': ['AAPL','AMZN','JNJ','SPY','BRK-A'],
              'Aggressive': ['GOOGL','TSLA','NVDA','FB','BABA'],
              'Growth': ['PLTR','AMD','TSLA','UPST','CRM'],
              }

for category in portfolios:
    sum = 0
    message="" 
    for stock in portfolios[category]:
        info = yf.Ticker(stock).info
        marketprice = info.get('regularMarketPrice')
        sum += marketprice
        message+= stock +","+ str(round(marketprice,2))+","
        #print(message)
#hidden character before category to parse or dictionary
    message+= ‎ category +","+ str(round(sum,2))
    print(message)
    #print (category + ": " + str(round(sum,2)))


#def Messenger : (message)
    credentials = pika.PlainCredentials(username='test', password='test')
    connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.61', credentials=credentials))

    channel = connection.channel()

    channel.queue_declare(queue='stock')

    channel.basic_publish(exchange='',
                    routing_key='stock',
                    body= message)
    print('Sent', message)
    connection.close()