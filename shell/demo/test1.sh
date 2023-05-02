#!/bin/bash
#syncAttend 筛选服务并杀死pid
ps -ef |grep syncAttend

input1=syncAttend

PID=$(ps x | grep  syncAttend| grep -v grep | awk '{print $1}')

if [ $? -eq 0 ]; then
    echo "process id:$PID"
else
    echo "process $input1 not exit"
    exit
fi

kill -9 ${PID}

if [ $? -eq 0 ];then
    echo "kill $input1 success"
else
    echo "kill $input1 fail"
fi
