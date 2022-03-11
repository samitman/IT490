#!/usr/bin/env python3
from bs4 import BeautifulSoup
import requests
import pika

#webscrape for TSLA price from yahoo finance
url = 'https://finance.yahoo.com/quote/TSLA/'
page = requests.get(url, headers={"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36",})
soup = BeautifulSoup(page.text, 'html.parser')
stockpricelist = soup.find('fin-streamer', {'class': 'Fw(b) Fz(36px) Mb(-4px) D(ib)'}).text

print(stockpricelist)

credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.60', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='stock')

channel.basic_publish(exchange='',
                  routing_key='hello',
                  body= stockpricelist)
print(" [x] Sent 'Webscraping data from yahoo finance'")
connection.close()