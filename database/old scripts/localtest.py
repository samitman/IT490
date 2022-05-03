import sys, os, mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)

pricefetch =('SELECT Price FROM stocks')
cursor = mydb.cursor()
cursor.execute(pricefetch)
etfprices = cursor.fetchall()
print(type(etfprices))
print(etfprices)

