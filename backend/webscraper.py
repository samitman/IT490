from bs4 import BeautifulSoup
import requests


url = 'https://finance.yahoo.com/quote/TSLA/'
page = requests.get(url, headers={"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36",})
soup = BeautifulSoup(page.text, 'html.parser')
price = soup.find('fin-streamer', {'class': 'Fw(b) Fz(36px) Mb(-4px) D(ib)'}).text
#price = soup.find('fin-streamer', {'class':'D(ib) Mend(20px)'}).find_all('fin-streamer')[0].text
print(price)