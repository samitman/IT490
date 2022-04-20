import sys, os, mysql.connector

def dbinsertion(depositDict):
    mydb = mysql.connector.connect(
  host="localhost",
  user="test",
  password="1234",
  database='test'
)
    msg = ''
    print(depositDict)
    cursor = mydb.cursor()
    select_stmt=('SELECT * FROM accounts WHERE Username = %(Username)s')
    #UPDATE accounts set balance = balance + %(Deposit)s where Username = %(Username)
    cursor.execute(select_stmt, depositDict)
    account = cursor.fetchone()
    if account:
        print("Account found...")
        insert_stmt=('UPDATE accounts SET Balance = Balance+%(Deposit)s WHERE Username = %(Username)s')
        cursor.execute(insert_stmt, depositDict)
        print("Deposit of: "+str(depositDict[1])+" made")
        mydb.commit()
        msg = '1'
        return msg
    else:
        print("Deposit failed")
        msg = '0'
        return msg


depositDict =  {"Username": 'test',"Deposit": '420.69' }    
response = dbinsertion(depositDict)

print(response)