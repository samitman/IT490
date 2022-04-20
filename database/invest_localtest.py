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
        insert_stmt=('UPDATE accounts SET Balance = Balance+%(Amount)s WHERE Username = %(Username)s')
        cursor.execute(insert_stmt, investDict)
        print("Balance adjusted")
        mydb.commit()

        insert_stmt=('UPDATE accounts SET {field} = {field}+%(Shares)s WHERE Username = %(Username)s').format(field=investDict["etfName"])
        cursor.execute(insert_stmt, investDict)
        mydb.commit()
        print("Shares allocated to user account!")


        msg = '1'
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