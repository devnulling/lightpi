#!/bin/bash
gpio mode 1 out
gpio mode 0 out
gpio write 0 0
gpio write 1 0
gpio write 0 1
gpio write 1 1
