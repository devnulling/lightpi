#!/bin/bash
/usr/local/bin/gpio mode 1 out
sleep 1
/usr/local/bin/gpio mode 0 out
sleep 1
/usr/local/bin/gpio write 0 1
sleep 1
/usr/local/bin/gpio write 1 1
sleep 1
