import sys, os, mysql.connector

def dbinsertion(credsdict):
    mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)
    msg = ''
    print(credsdict)
    cursor = mydb.cursor()
    select_stmt=('SELECT * FROM accounts WHERE Username = %(Username)s AND Password = %(Password)s')
    cursor.execute(select_stmt, credsdict)
    account = cursor.fetchone()
    print("This is what you get when you do fetchone:"+account)
    if account:
        print("Account found, logging in!")
        msg = '1'
        return msg
    else:
        print("Account doesn't exist or username/password incorrect")
        msg = '0'
        return msg

depositDict =  {"Username": 'test',"Password": '1234' }    
response = dbinsertion(depositDict)

print(response)