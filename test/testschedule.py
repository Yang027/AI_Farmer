import functools
import logging
import os
import re
import time
from datetime import datetime, timedelta
import paho.mqtt.client as mqtt
import schedule
from schedule import CancelJob, IntervalError, Job
#mqtt param 
Server="192.192.156.30"
Port=1883
Alivetime=60
MQTTUsername='yang'
MQTTPassword='1234'
#SQL VALUE 
 
# 每個小時執行任務
class Mqtt:               

    #mqtt連接host成功，並訂閲主題
    def on_connect(self,client, userdata, flags, rc):
        print("Connected with result code "+str(rc))
        self.Client.subscribe(self.Subscribe)

    #收到訂閲的topic傳來的message
    def on_message(self,client, userdata, msg):
        global xx
        print(msg.topic)
        if(msg.topic=='gh_abner_yang_01/update'):
                   #初始化要送出去的mqtt payload，payload的形態如最上方定義的truth table   
            print(msg)
            #"gh_abner_yang_01/temp"
            schedule.clear("gh_abner_yang_01/temp")
            schedule.every(1).day.at("15:57").do(job,"gh","ol").tag("gh_abner_yang_01/temp")    

    #自定義mqtt的建構值，初始化mqtt連綫的基本參數，如：server，port，username，password，訂閲主題，發佈主題，alivetime       
    def __init__(self,_server,_port,_username,_password,_subscribe,_alivetime):
        self.Server=_server
        self.Port=_port
        self.Username=_username
        self.Password=_password
        self.Subscribe=_subscribe
        #self.Publish=_publish
        self.Alivetime=_alivetime
        self.setpar=False
        self.Client = mqtt.Client()  
        self.Client.on_connect = self.on_connect
        self.Client.on_message = self.on_message
        self.Client.username_pw_set(self.Username,self.Password)    
        self.Client.connect(self.Server,self.Port,self.Alivetime)

class MyJob(Job):  
    def __init__(self, scheduler=None):  
        super(MyJob, self).__init__(1, scheduler)  
        self.regex =  re.compile(r'((?P<hours>\d+?)hr)?((?P<minutes>\d+?)m)?((?P<seconds>\d+?)s)?')
  
    def parse_time(self, time_str):  
        # https://stackoverflow.com/questions/4628122/how-to-construct-a-timedelta-object-from-a-simple-string  
        parts = self.regex.match(time_str)  
        if not parts:  
            raise IntervalError()  
  
        parts = parts.groupdict()  
        time_params = {}  
        for (name, param) in parts.items():  
            if param:  
                time_params[name] = int(param)  
  
        return timedelta(**time_params)  
  
    def do(self, job_func, *args, **kwargs):  
        self.job_func = functools.partial(job_func, *args, **kwargs)  
        try:  
            functools.update_wrapper(self.job_func, job_func)  
        except AttributeError:  
            # job_funcs already wrapped by functools.partial won't have  
            # __name__, __module__ or __doc__ and the update_wrapper()  
            # call will fail.  
            pass  
  
        self.scheduler.jobs.append(self)  
        return self  
  
    def after(self, atime):  
        if isinstance(atime, timedelta):  
            self.next_run = datetime.now() + atime  
        elif isinstance(atime, str):  
            times = atime.split(':')  
            if len(times) == 3:  # HH:MM:SS  
                self.next_run = datetime.now() + timedelta(hours=int(times[0]), minutes=int(times[1]), seconds=int(times[2]))  
            else:  
                self.next_run = datetime.now() + self.parse_time(atime)  
        else:  
            raise IntervalError()  
  
        return self  
  
    def run(self):  
        #print('Running job %s', self)  
        ret = self.job_func()  
        self.last_run = datetime.now()  
        print(f'now datatime:{str(datetime.now())}')
        return CancelJob()  
#排程expression
def work(a,b,c,d):
    print('close')
from datetime import datetime

#print(datetime.datetime.now())
def job(ghid,payloads):#greenhouse_id,control(e.g:light,fan),(HH:mm)<--format time
    #https://stackoverflow.com/questions/65092808/how-get-all-messages-when-new-client-subscribe-instead-last-one-message-mqtt-r
    #mqtt新client無法讀取上一次發送的内容，除非就是新增資料表去讀取上次狀態
    #排程邏輯：時間到，先讀取狀態是什麽，在去做動作
    #mqttclient.publish(ghid+"/control", payloads)
    print(ghid+","+payloads)
    print(f'now datatime:{str(datetime.now())}')
    #myjob.after('2s').do(work,1,2,3,4)  # Do work after 2 minutes 
   
    #schedule.every().day.at(caculatetime(fantime)).do(job(i,"cf"))
Subtopic='gh_abner_yang_01/update'
mqttclient=Mqtt(Server,Port,MQTTUsername,MQTTPassword,Subtopic,Alivetime)#建立mqtt連綫  
schedule.every(1).day.at("15:56").do(job,"gh","ol").tag("gh_abner_yang_01/temp")
      
 
while True:
    mqttclient.Client.loop_start()#loop_forever()#hold住連綫狀態 
    schedule.run_pending()
   
 