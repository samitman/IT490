import requests
import pika
from bs4 import BeautifulSoup

#yahoo ticker
def create_url(ticker):
    url = 'https://finance.yahoo.com/quote/' + ticker
    return url

def get_html(url):
    header = {"User Agent": 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36'}
    response = requests.get(url, headers = header)

    if response.status_code == 200:
        return response.text
    else:
        return None


def parse_data(html):

    soup = BeautifulSoup(html,'html.parser')
    name = soup.find('h1', {'class': 'D(ib) Fz(18px)'}).text

    price = soup.select_one('#quote-header-info > div.My(6px).Pos(r).smartphone_Mt(6px).W(100%) > div.D(ib).Va(m).Maw(65%).Ov(h) > div.D(ib).Mend(20px) > fin-streamer.Fw(b).Fz(36px).Mb(-4px).D(ib)').text

    stockpricelist = {
        'name':name,
        'price':price,
    }

    return stockpricelist

def main():
    tickers = ["AAPL", "TSLA", "AMZN"]

    for ticker in tickers:
        url = create_url(ticker)

        html = get_html(url)

        data = parse_data(html)

        print(data) 

if __name__ == '__main__':
    main()

#if it doesnt work replace body with 'data' or 'stockpricelist' instead
credentials = pika.PlainCredentials(username='test', password='test')
connection = pika.BlockingConnection(pika.ConnectionParameters(host='192.168.192.61', credentials=credentials))

channel = connection.channel()

channel.queue_declare(queue='user')

channel.basic_publish(exchange='',
                  routing_key='user',
                  body= main)
print(" [x] Sent 'Webscraping data from yahoo finance'")
connection.close()