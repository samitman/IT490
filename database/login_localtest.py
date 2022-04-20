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
    print("This is what you get when you do fetchone:"+str(account))
    if account:
        print("Account found, logging in!")
        uid = str(account[0])
        email = str(account[1])
        username = str(account[2])
        first = str(account[4])
        last = str(account[5])
        balance = str(account[6])
        eftMeme = str(account[7])
        eftBoomer = str(account[8])
        eftTech = str(account[9])
        eftCrypto = str(account[10])
        eftModerate = str(account[11])
        eftAggressive = str(account[12])
        eftGrowth = str(account[13])
        

        msg = str(uid+","+email+","+username+","+first+","+last+","+balance+","+eftMeme+","+eftBoomer+","+eftTech+","+eftCrypto+","+eftModerate+","+eftAggressive+","+eftGrowth)
        return msg
    else:
        print("Account doesn't exist or username/password incorrect")
        msg = '0'
        return msg

depositDict =  {"Username": 'test',"Password": '1234' }    
response = dbinsertion(depositDict)

print(response)