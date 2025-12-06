#!/bin/bash

apt --installed | grep ufw 

if[$? -eq 0]; then
    ufw status | grep 9003
    if[$? -eq 0]; then
        exit 0;
    fi
    ufw allow in from 192.168.0.1/24 to any port 9003
else
    iptables -t filter -A INPUT -p tcp -m -s 192.168.0.1/24 --dport 9003 -j INPUT
fi