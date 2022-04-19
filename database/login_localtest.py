from plistlib import UID
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
        uid = account[0]
        email = account[1]
        username = account[2]
        first = account[4]
        last = account[5]
        balance = account[6]
        eftMeme = account[7]
        eftBoomer = account[8]
        eftTech = account[9]
        eftCrypto = account[10]
        eftModerate = account[11]
        eftAggressive = account[12]
        eftGrowth = account[13]

        msg = str(uid+","+email+","+username+","+first+","+last+","+balance+","+eftMeme+","+eftBoomer+","+eftTech+","+eftCrypto+","+eftModerate+","+eftAggressive+","+eftGrowth)
        return msg
    else:
        print("Account doesn't exist or username/password incorrect")
        msg = '0'
        return msg

depositDict =  {"Username": 'test',"Password": '1234' }    
response = dbinsertion(depositDict)

print(response)