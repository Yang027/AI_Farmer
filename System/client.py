# -*- coding: utf-8 -*-
"""
Created on Tue Jul  1 23:48:08 2022
@author: yang
@project:suscribe greenhouse sensor value,and control the value by loading param from mysql

生長周期：
Step 1->幼苗期
Step 2->成長期
Step 3->開花期
Step 4->結果期
 
mqtt publish payload content's Truth Table:
    hot  water  light  Fan
    0     0      0     0   全關                        --1
    0     0      0     1   開風扇                      --2
    0     0      1     0   開燈                        --3
    0     0      1     1   澆水&開風扇                 --4
    0     1      0     0   澆水                        --5
    0     1      0     1   開風扇&水                   --6
    0     1      1     0   澆水&開燈                   --7
    0     1      1     1   開燈&開風扇&澆水            --8
    1     0      0     0   開除濕                     --9
    1     0      0     1   開除濕&開風扇               --10
    1     0      1     0   開除濕&燈                  --11
    1     0      1     1   開除濕&燈&風扇             --12
    1     1      0     0   開除濕&澆水                --13
    1     1      0     1   開除濕&澆水&風扇           --14
    1     1      1     0   開除濕&澆水&開燈           --15
    1     1      1     1   全開                      --16
#https://github.com/line/line-bot-sdk-python    <---line sdk 
#https://ithelp.ithome.com.tw/articles/10227131 <---mqtt client

step1:host會依序訂閲所有user的mqtt topic
step2:等到mqtt topic的資料收回來
"""
#pip install paho-mqtt
#pip install mysql
 
 
import calendar
import functools
import logging
import math
import os
import re
import time
from datetime import datetime, timedelta
from re import T
from tokenize import Double
from unittest import skip
import schedule
import paho.mqtt.client as mqtt
from schedule import CancelJob, IntervalError, Job
import MySQLdb
####################GLOBAL VALUE#########################
Growth_cycle=['幼苗期','成長期','開花期','結果期']
#plant api
Plant='tomato'
PlantCir=''#周期
#mqtt param 
Server="mqttserver"
Port=1883
Alivetime=60
MQTTUsername='mqttusr'
MQTTPassword='mqttpwd'
#SQL VALUE 
Db='dbname'
DBUsername='db'
DBPassword='pwd
DBTableName='plant'      
#初始化溫室預設環境資料
#除了co2和lux，其他單位皆以0-100來計算
Temp_MAXVALUE=30
Temp_MINVALUE=10
Lux_MAXVALUE=10000
Lux_MINVALUE=100
Soil_MAXVALUE=90
Soil_MINVALUE=70
Humi_MAXVALUE=50
Humi_MINVALUE=60
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
        #print(f'now datatime:{str(datetime.now())}')
        return CancelJob()  
#定義連接資料庫的方法
class Sql:
    #初始化，連接資料庫
    def __init__(self,_server,_username,_password,db,dbTable):
        self.db = MySQLdb.connect(_server, _username, _password, db, charset='utf8')
        self.cursor = self.db.cursor()
        self.table=dbTable
        print("connect success")           
        
    #查詢
    def inquery(self,sqlstr):          
        sql =sqlstr  # SQL 查詢語句
        try:          
            self.cursor.execute(sql)  # 執行SQL指令           
            results = self.cursor.fetchall() # 獲取運行結果 
            return results
        except:
            print ("Error: unable to fetch data")

    #插入
    def insert(self,sqlstr):
        sql=sqlstr
        try:
            self.cursor.execute(sql)
            self.db.commit()             
        except Exception as e:
            print (e,"Error: unable to insert data")

    #關閉連綫
    def close(self):
        self.db.close()

#define mqtt setting
class Mqtt:               

    #mqtt連接host成功，並訂閲主題
    def on_connect(self,client, userdata, flags, rc):
        print("Connected with result code "+str(rc))
        print('start to subscribe topic...')
        for sub in self.Subscribe:
            print(f'Subscribed topic:{sub}')
            self.Client.subscribe(sub)

    #收到訂閲的topic傳來的message
    def on_message(self,client, userdata, msg):
        #初始化要送出去的mqtt payload，payload的形態如最上方定義的truth table   
        fan=0
        bump=0
        light=0
        hot=0  
        #取得global 資料
        global Temp_MAXVALUE    
        global Temp_MINVALUE 
        global Humi_MAXVALUE 
        global Humi_MINVALUE 
        global Lux_MAXVALUE 
        global Soil_MAXVALUE
        global Soil_MINVALUE    
        mysql=Sql(Server,DBUsername,DBPassword,Db,DBTableName)   
        if('/update' in msg.topic):
            #更新了
            Greenhouse_id=msg.topic.split('/update')[0]
            schedule.clear(Greenhouse_id)
            print("updating customer Value...")
            getCustomV(Greenhouse_id,mysql,self)#greenhouseid list,mysql object
        
        else:
            Greenhouse_id=msg.topic.split('/sensor')[0]
            print('------------------------------------------------------------------------')
            print(f'greenhouse_id={Greenhouse_id}')
            #print(Greenhouse_id)
            

            # 轉換編碼utf-8才看得懂中文
            #print(msg.topic+" "+ msg.payload.decode('utf-8'))
            _payload=msg.payload.decode('utf-8')#對接收到的資料進行解碼
            if('MQTT' in _payload):
                print('connected')
            else:
                #將解碼的資料進行處理，分成溫度、濕度、二氧化碳和光照值
                tmp=_payload.split(',')        
                temp=tmp[0]
                humid=tmp[1] 
                co2=tmp[2]
                lux=tmp[3]       
                #因爲土壤sensor可能會有≤0的狀況 故要做預處理，如果小於0，設爲0
                soil='0' if eval(tmp[4])<0 else tmp[4]
                #print("溫室環境溫度：%s \n溫室環境濕度：%s \n溫室二氧化碳濃度(ppm)：%s \n溫室光照值(lux)：%s \n土壤濕度：%s"%(temp,humid,co2,lux,soil))


                #取得目前溫室内種植的植物和開始種植時間的資料
                sqlstr=f"SELECT start_time,plant_id FROM `greenhouse_plant` WHERE `greenhouse_id`='{Greenhouse_id}' and `end_time`=0"
                results=mysql.inquery(sqlstr)     
                #資料處理
                starttime=results[0][0]#當前種植作物的開始時間
                Plant_ID=results[0][1]#當前種植的作物
                #username=Greenhouse_id.split('gh_')[1].split('_0')[0]
                # #使用者名稱
                sqlstr=f'SELECT `username` from `google_users` where email in (SELECT mail FROM `usergreenhouse` WHERE Greenhouse_id="{Greenhouse_id}")'
                results=mysql.inquery(sqlstr)  
                username=results[0][0]
                print(f'current user is {username}')
                currTime = datetime.utcnow()#取得現在時間
                unix_time = calendar.timegm(currTime.utctimetuple())#將現在的時間轉換成unix time    
                gc=-1
            
                ### 將前面收集到的溫室環境資料上傳到mysql
                print('update sensor data to database...')
                #add temp humid co2 data，加入收集到的co2,temp,humid資料
                sqlstr=f'INSERT INTO `air_sensor_data` (sensor_id,record_time,co2,temperature,humidity,start_time,greenhouse_id)  VALUES ("ah_{username}01", {unix_time},{co2},{temp},{humid},{starttime},"{Greenhouse_id}");'
                results=mysql.insert(sqlstr)      
                #add lux  加入收集到的光照值
                sqlstr=f'INSERT INTO `lux_sensor_data` (sensor_id,record_time,accumulation_lux,start_time,greenhouse_id)  VALUES ("lux_{username}01",{unix_time},{lux},{starttime},"{Greenhouse_id}");'
                results=mysql.insert(sqlstr)      
                #add soil 加入收集到的土壤濕度
                #先檢查溫室内有沒有土壤傳感器
                #SELECT * FROM `greenhouse_soil`
                sqlstr=f"SELECT `greenhouse_soil`.`soil_sensor_id` FROM `greenhouse_soil` where `greenhouse_soil`.`greenhouse_id`='{Greenhouse_id}' and `greenhouse_soil`.`start_time`='{starttime}'"
                results=mysql.insert(sqlstr)  
                '''
                print(sqlstr)
                print(results)
                '''
                #if(results==None or len(results)==0):
                #    print('no soil sensor in the greenhouse.')
                #else:
                ###這邊是暫時只有加入一個土壤濕度，因爲arduino發送的資料并沒有兩個的設定###
                #修改方式：
                #根據arduino發送的payload，可能soil_sensor那會有額外的分割方式，根據處理出來的index+1去對應資料庫中傳感器id
                #e.g -->payload={12,13,14,15|43|53,16}
                #切出來，分成三個soilsensor-->soil[0]=15,soil[1]=43,soil[2]=53
                #再根據username 和 index+1 去修改 insert的soil sensor參數，ssi_{username}{index+1}
                sqlstr=f'INSERT INTO `soil_sensor_data` (sensor_id,record_time,soil_humidity,start_time)  VALUES ("ssi_{username}01", {unix_time},{soil},{starttime});'
                results=mysql.insert(sqlstr)     
                print('done.')    
                
                if(self.setpar==False):#有加載種植參數才會進來            
                    #user setting first
                    #co2,humidity,need_lux_value,temperature,soil_humidity
                    sqlstr=f"SELECT `co2`,`humidity`,`need_lux_value`,`temperature`,`soil_humidity` FROM `greenhouse_customv` WHERE greenhouse_id='{Greenhouse_id}'"
                    results=mysql.inquery(sqlstr) 
                    if(len(results)>0):
                        print('setting up custom plant parameter')
                        for row in results: 
                            print(row)
                            #user have setting customer enviormental model
                            Temp_MAXVALUE=row[3]+3 #  if row[3]>0  else Temp_MAXVALUE
                            Temp_MINVALUE=row[3]-3 #  if row[4]>0  else Temp_MINVALUE
                            Humi_MAXVALUE=row[1]+3 #  if row[5]>0  else Humi_MAXVALUE
                            Humi_MINVALUE=row[1]-3 #  if row[6]>0  else Humi_MINVALUE
                            Lux_MAXVALUE=row[2]  #  if row[7]>0  else Lux_MAXVALUE
                            Soil_MAXVALUE=row[4]+5 #  if row[9]>0  else Soil_MAXVALUE
                            Soil_MINVALUE=row[4]-5#  if row[10]>0 else Soil_MINVALUE   
                        
                    else: 
                        sqlstr=f"SELECT `growth_cycle` FROM `growth_cycle` WHERE `greenhouse_id`='{Greenhouse_id}' and `start_time`='{starttime}' ORDER BY `growth_cycle`.`record_time` DESC"
                        results=mysql.inquery(sqlstr)  
                        #print(len(results))
                        if(len(results)>0):
                            print('setting up  plant parameter')
                            global Growth_cycle
                            gc=Growth_cycle[int(results[0][0])-1]#取得目前溫室内作物的生長周期
                            self.plant_param(Plant_ID,Growth_cycle)#設定好環境參數
                    print('done.')             

                #自動環控
                if(self.setpar==True):#有加載種植參數才會進來 或是 有設定時間 。基本上一定會進來 因爲系統有預設環境參數        
                    #去mysql查詢當前作物目前生長周期的環境參數爲何，e.g plant_id=番茄 groth_cycle='開花期'，獲取資料庫内番茄開花期的環境資料  
                    results=None
                    if(gc!=-1):      
                        sqlstr=f"SELECT * FROM `environmental` WHERE `plant_id`='{Plant_ID}' and`growth_cycle`='{gc}'"
                        results=mysql.inquery(sqlstr)      
                        print(f'current growth_cycle is {gc}.')
                    #沒有生長周期的資料
                    print('getting environmental data of plant...')
                    if(results!=None):
                        for row in results:                  
                            Temp_MAXVALUE=row[3] #  if row[3]>0  else Temp_MAXVALUE
                            Temp_MINVALUE=row[4] #  if row[4]>0  else Temp_MINVALUE
                            Humi_MAXVALUE=row[5] #  if row[5]>0  else Humi_MAXVALUE
                            Humi_MINVALUE=row[6] #  if row[6]>0  else Humi_MINVALUE
                            Lux_MAXVALUE=row[7]  #  if row[7]>0  else Lux_MAXVALUE
                            Soil_MAXVALUE=row[9] #  if row[9]>0  else Soil_MAXVALUE
                            Soil_MINVALUE=row[10]#  if row[10]>0 else Soil_MINVALUE   
                            print('done.')
                    else :
                        #no record the data 資料庫内沒有環境資料，使用系統預設的資料
                        print("there is no data in database,use default setting")   
                ##                 plan B                   
                # if Temp_MAXVALUE!=None or Temp_MINVALUE!=None:#temp
                #     if float(temp)>float(Temp_MAXVALUE):   #降溫
                #         client.publish(Greenhouse_id+"/control", "of")
                #         print(f'sending message to host,payload is <of>')
                #     elif  float(temp)<float(Temp_MINVALUE):#升溫 
                #         client.publish(Greenhouse_id+"/control", "oh")
                #         print(f'sending message to host,payload is <oh>')       
                #     else:
                #         client.publish(Greenhouse_id+"/control", "cf")
                #         client.publish(Greenhouse_id+"/control", "ch")
                #         print(f'sending message to host,payload is <cf,ch>')      
                                                
                if humid!=None: #humid
                    if float(humid)>float(Humi_MAXVALUE):  #濕度太濕
                        hot=1                    
                    elif  float(humid)<float(Humi_MINVALUE):#太乾
                        hot=0         
                if Temp_MAXVALUE!=None or Temp_MINVALUE!=None:#temp
                    if float(temp)>float(Temp_MAXVALUE):   #降溫
                        fan=1
                    elif  float(temp)<float(Temp_MINVALUE):#升溫 
                        hot=1  
                if Soil_MAXVALUE!=None or Soil_MINVALUE!=None:#soil 
                    if  float(soil)>float(Soil_MAXVALUE):  #土壤太濕                    
                        bump=0                     
                    elif  float(soil)<float(Soil_MINVALUE):#土壤太乾                     
                        bump=1   
                if Lux_MAXVALUE!=None:
                    if float(lux)<float(Lux_MAXVALUE):
                        light=1
                    else:
                        light=0
                # need to add lux here!!!
                action=str(hot)+str(bump)+str(light)+str(fan)
                print(f'sending message to host,payload is <{action}>')
                #發送payload，執行環控
                client.publish(Greenhouse_id+"/control", action)
                print('------------------------------------------------------------------------')
                #關閉資料庫的連綫
        
              
        mysql.close()

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

    #確認目前使用者所種植的植物和生長周期   
    def plant_param(self,plantid,circle):
        self.plantid=plantid
        self.circle=circle
        self.setpar=True

#時間轉換
def caculatetime(time):#minute
    hour=math.floor(time/60)
    minute=time%60
    hh='0'+str(hour) if hour<10 else str(hour)
    min='0'+str(minute) if minute<10 else str(minute)
    return f"{hh}:{min}"


def close(ghid,payloads,mqttlicent):
    #排程 close 掉前面的定時
    mqttclient.Client.publish(ghid+"/control", payloads,qos=2,retain=True)
    print('close.')

#排程expression
def job(ghid,payloads,mqttclient):#greenhouse_id,control(e.g:light,fan),(HH:mm)<--format time
    #https://stackoverflow.com/questions/65092808/how-get-all-messages-when-new-client-subscribe-instead-last-one-message-mqtt-r
    #mqtt新client無法讀取上一次發送的内容，除非就是新增資料表去讀取上次狀態
    #排程邏輯：時間到，先讀取狀態是什麽，在去做動作
    print(type(mqttclient))
    mqttclient.Client.publish(ghid+"/control", payloads,qos=2,retain=True)
    myjob = MyJob(schedule.default_scheduler)
    print(f'topic:{ghid+"/control"},payloads-->{payloads}')
    if(payloads=='of'):
        print('開風扇')
        _payload='cf'
    elif(payloads=='oh'):
        print('開除濕')
        _payload ='ch' 
    elif(payloads=='ob'):
        print('澆水')
        _payload='cb'
    elif(payloads=='ol'):
        print('開燈')
        _payload='cl'
    try:
        #param in after if :-->h,m,s
        myjob.after('5m').do(close,ghid,_payload,mqttclient)  # Do work after  hour/minutes/or second
        time.sleep(2)
    except Exception as e:
        print(e) 

#檢查有沒有間隔兩小時
def checktime(time1,time2):#return true if time exceed two hours
    return True if abs(time1-time2)>120 else False

#取得使用者自定義時間
def getCustomV(Greenhouse_id,mysql,mqttclient):#greenhouseid,mysql object，mqtt object
     #取得user排程的時間   
    #for i in Greenhouse_id:
    sqlstr=f"SELECT fantime,hottime,bumptime,luxtime FROM `greenhouse_customT` WHERE `greenhouse_id`='{Greenhouse_id}'"
    cresults=mysql.inquery(sqlstr)   #user有沒有設定客制化的時間
    print(f'greenhouse:<{Greenhouse_id}>,cresults={cresults},len(cresult)={len(cresults)}')
    #開啓后兩個小時自己關掉!!
    #若user有開啓其中之一的設定，則其他預設的時間為-1
    if(len(cresults)>0):
        fantime=cresults[0][0]#caculatetime(cresults[0][0])#將收到的時間轉成utc
        hottime=cresults[0][1]#caculatetime(cresults[0][1])
        bumptime=cresults[0][2]#caculatetime(cresults[0][2])            
        luxtime=cresults[0][3]#caculatetime(cresults[0][4])             
        # hot  water  light  Fan
            #先去檢查其他的有沒有間隔兩小時 
        if fantime>0:       
            schedule.every(1).day.at(caculatetime(fantime)).do(job,Greenhouse_id,"of",mqttclient).tag(i)
        if hottime>0:                
            schedule.every(1).day.at(caculatetime(hottime)).do(job,Greenhouse_id,"oh",mqttclient).tag(i)  
        if bumptime>0:   
            schedule.every(1).day.at(caculatetime(bumptime)).do(job,Greenhouse_id,"ob",mqttclient).tag(i)    
        if luxtime>0:
            schedule.every(1).day.at(caculatetime(luxtime)).do(job,Greenhouse_id,"ol",mqttclient).tag(i)               
    print('get user customer data time')  if(len(cresults)>0) else print('user have not set up customer time')

if __name__=='__main__':    
    mysql=Sql(Server,DBUsername,DBPassword,Db,DBTableName)  
    sqlstr="SELECT `greenhouse_plant`.`greenhouse_id` FROM `greenhouse_plant` WHERE end_time=0"#取得所有還在種植，沒有結束的溫室
    results=mysql.inquery(sqlstr)      
    Greenhouse_id=set() #所有不重複的greenhouseid
    for i in results:
        Greenhouse_id.add(i[0])
    Subtopic=[]#訂閲主題
    for i in Greenhouse_id:
        Subtopic.append(f'{i}/sensor')
        Subtopic.append(f'{i}/update')
    mqttclient=Mqtt(Server,Port,MQTTUsername,MQTTPassword,Subtopic,Alivetime)#建立mqtt連綫  
    for i in Greenhouse_id:
        getCustomV(i,mysql,mqttclient)
    mysql.close()
    while(True):
        schedule.run_pending()
        mqttclient.Client.loop_start()#loop_forever()#hold住連綫狀態 

        
    
    
