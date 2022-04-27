import requests
import pika
import yfinance as yf



def messenger(message):
    print("[X] Sent :"+message)
    credentials = pika.PlainCredentials(username='test', password='test')
    connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

    channel = connection.channel()

    channel.queue_declare(queue='stock')

    channel.basic_publish(exchange='',
                    routing_key='stock',
                    body= message)
    print('Sent', message)
    connection.close()

portfolios = {'etfMeme': ['GME', 'AMC', 'DOGE-USD','DWAC','CLOV'],
              'etfBoomer': ['WMT','KO','JNJ','T','UNH'],
              'etfTech': ['AMD','TSLA','MSFT','GOOGL','AAPL'],
              'etfCrypto': ['BTC-USD','ETH-USD','BNB-USD','XRP-USD','ADA-USD'],
              'etfModerate': ['AAPL','AMZN','JNJ','SPY','BRK-A'],
              'etfAggressive': ['GOOGL','TSLA','NVDA','FB','BABA'],
              'etfGrowth': ['PLTR','AMD','TSLA','UPST','CRM'],
              }

for category in portfolios:
    sum = 0
    for stock in portfolios[category]:
        info = yf.Ticker(stock).info
        marketprice = info.get('regularMarketPrice')
        sum += marketprice
        #stockstring= stock +","+ str(round(marketprice,2))
        #messenger(stockstring)
    portfoliostring= category +","+ str(round(sum,2))
    messenger(portfoliostring)
