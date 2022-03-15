import requests
import pika
import yfinance as yf

stocks = ['AAPL', 'MSFT', 'AMD']

#def main():
for stock in stocks:
    info = yf.Ticker(stock).info
    marketprice = info.get('regularMarketPrice')
    print(stock, marketprice)

#if __name__ == '__main__':
#    main()

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='user')

channel.basic_publish(exchange='',
                  routing_key='user',
                  body= stock)
print(" [x] Sent 'Webscraping data from yahoo finance'")
connection.close()