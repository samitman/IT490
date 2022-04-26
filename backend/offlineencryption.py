
body = 'ryan@yahoo.com,ryanm,password123,ryan,mar'
def on_request(body):
    #print(" [x] Received %r" % body)
    #print(type(body))

    #messagestring = body.decode()
    credslist = body.split(',')
    print(credslist)    
    email = credslist[0]
    username = credslist[1]
    plainpassword = credslist[2]
    firstName = credslist[3]
    lastName = credslist[4]

    print("Split check:" + email +" "+ username +" "+ plainpassword +" "+ firstName +" "+ lastName)
    print(credslist)
    credsdict =  {"Email": email,"Username": username,"Password": plainpassword,"First Name": firstName,"Last Name": lastName }

    def gethashpass(plainpassword):
        return bcrypt.hashpw(plainpassword,bcrypt.gensalt())

    hashedpassword = gethashpass(plainpassword)
    print(hashedpassword)
    return hashedpassword
response = on_request(body)
print(response)


def checkhash(password,storedhashpass):
    return bcrypt.checkpw(password, storedhashpass)

password = 'password123'
storedhash = '$2a$12$izKi4jESEOHVIjGP4LmjK.Zqv/RiT9mnSxFgTj8Wj4./jwtn0PxDa'
answer = checkhash(password, storedhash)
print(answer)


    #checks hash / salt saved onto hash
    #def checkhash(password,hashpass):
    #    return bcrypt.checkpw(password, hashpass)

    #salt = uuid.uuid4().bytes
    #hashedpass = hashlib.sha512(password + salt).digest()

    #call "be_reg2.py username password email first and last name

    #response = main(email,username,hashedpassword,firstName,lastName) #FROM BE TO DB