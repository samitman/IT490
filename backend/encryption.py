#!/usr/bin/env python3
import hashlib

#user's password
plaintext = "ryanpassword".encode()

#instantiate sha3_256 object
d = hashlib.sha3_256(plaintext)

#generates hash of "ryanpassword" 
#should produce similar to: "38\xbeiOP\xc5\xf38\x81I\x86\xcd\xf0hdS\xa8\x88\xb8OBMy*\xf4\xb9 #\x98\xf3\x92"
hash = d.digest()
print(hash)

#generates readable hash of "ryanpassword" string
#should produce similar to: "3338be694f50c5f338814986cdf0686453a888b84f424d792af4b9202398f392"
hash = d.hexdigest()
print(hash)


################
#if the previous code does not work use the following:
#import os
#import hashlib

#salt = os.urandom(32)
#plaintext = 'hellow0rld'.encode()

#digest = hashlib.pbkdf2_hmac('sha256', plaintext, salt, 10000)

#hex_hash = digest.hex()
#print(hex_hash)

#byte_hash = digest.fromhex(digest.hex())
#print(byte_hash)