import sys, os, mysql.connector

def dbinsertion(investDict):
    mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)
    msg = ''
    print(investDict)
    cursor = mydb.cursor()
    select_stmt=('SELECT * FROM accounts WHERE Username = %(Username)s')
    cursor.execute(select_stmt, investDict)
    account = cursor.fetchone()
    if account:
        print("Account found...")
        #inserting new balance into table
        insert_stmt=('UPDATE accounts SET Balance = Balance+%(Amount)s WHERE Username = %(Username)s')
        cursor.execute(insert_stmt, investDict)
        print("Balance adjusted")
        mydb.commit()

        #inserting shares into table
        insert_stmt=('UPDATE accounts SET {field} = {field}+%(Shares)s WHERE Username = %(Username)s').format(field=investDict["etfName"])
        cursor.execute(insert_stmt, investDict)
        mydb.commit()
        print("Shares allocated to user account!")

        
        #constructing final message to be sent to FE
        select_stmt=('SELECT Balance FROM accounts WHERE Username = %(Username)s')
        cursor.execute(select_stmt, investDict)
        account = cursor.fetchone()
        newBalance = str(account[0])
        
        #grabbing price
        select_stmt=('SELECT Price FROM stocks WHERE Ticker = %(etfName)s')
        cursor.execute(select_stmt, investDict)
        price = cursor.fetchone()
        newPrice = str(price[0])

        
        msg = str(investDict["Username"]+","+investDict["Shares"]+","+newPrice+","+newBalance)
        return msg
    else:
        print("Investment failed, username not found")
        msg = '0'
        return msg

username = 'test'
etfName = 'etfGrowth'
Amount = '-4000'
sharesAmt = '4.20'

investDict =  {"Username": username, "etfName":etfName, "Amount": Amount, "Shares": sharesAmt }   
response = dbinsertion(investDict)

print(response)