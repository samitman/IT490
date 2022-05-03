#!/bin/bash
gnome-terminal -- "./db_invest.py" $SHELL;
gnome-terminal -- "./db_deposit.py" $SHELL;
gnome-terminal -- python3 db_gethash.py
gnome-terminal -- python3 db_getprice.py
gnome-terminal -- "./db_login.py" $SHELL;
gnome-terminal -- "./db_registration.py" $SHELL;
gnome-terminal -- "./db_sell.py" $SHELL;
gnome-terminal -- "./db_stocks.py" $SHELL;
gnome-terminal -- "./db_withdraw.py" $SHELL;