# -*- coding: utf-8 -*-
"""
Created on Tue Jul 19 10:14:32 2022

@author: y1595
"""
import calendar
import os
import sys
import threading
import time
from collections import deque
from datetime import datetime
from multiprocessing.dummy import Pool as ThreadPool
from chardet import detect
from schedule import CancelJob, IntervalError, Job
import MySQLdb
import cv2
import matplotlib.pyplot as plt
import numpy as np
import torch
from datetime import datetime, timedelta
import functools
import re
import schedule
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import requests

_time=set()
Db='aifarmer'
DBUsername='aifarmer'
DBPassword='1234'
DBTableName='plant'   
Mail=''
Server="192.192.156.30"
Greenhouse_id='gh_abner_yang_01'
dict_upload=False
current_directory = os.path.dirname(os.path.abspath(__file__))
imagePath=os.path.join(current_directory,"image");
starttime=-1
device = 'cuda' if torch.cuda.is_available() else 'cpu'
model = torch.hub.load('ultralytics/yolov5', 'yolov5s',pretrained=True)  # or yolov5n - yolov5x6, custom
model.to(device)
def line_notify(message,_token,image=None):
    # LINE Notify 權杖
    global count
    img=None
    try:
        name = "frame%d.jpg"%count
        cv2.imwrite(name,image) 
        img = open(f'frame{count}.jpg', 'rb')
        count=count+1;
    except Exception as e:
        print(f'gei fail---->{e}')
  
    token =_token    
    # HTTP 標頭參數與資料
    headers = { "Authorization": "Bearer " + token }
    data = { 'message': message }
    if img!=None:
        files = { 'imageFile': img }
    try:
        # 以 requests 發送 POST 請求
        if img!=None:
            requests.post("https://notify-api.line.me/api/notify",
            headers = headers, data = data,files=files)
        else:
             requests.post("https://notify-api.line.me/api/notify",
            headers = headers, data = data)
    except Exception as e:
        print(e)

def mail(pr):
    content = MIMEMultipart()  #建立MIMEMultipart物件
    content["subject"] = "溫室異常狀況提醒"  #郵件標題
    content["from"] = "aifarmer2022@gmail.com"  #寄件者
    content["to"] = "a108222027@mail.shu.edu.tw" #收件者
    content.attach(MIMEText(f"親愛的使用者你好:\n 您的溫室有異常狀況：{pr},請您及時查看"))  #郵件內容
    with smtplib.SMTP(host="smtp.gmail.com", port="587") as smtp:  # 設定SMTP伺服器
        try:
            smtp.ehlo()  # 驗證SMTP伺服器
            smtp.starttls()  # 建立加密傳輸
            smtp.login("aifarmer2022@gmail.com", "lpjcuxsoaqbtuece")  # 登入寄件者gmail
            smtp.send_message(content)  # 寄送郵件
            #print("Complete!")
        except Exception as e:
            print("Error message: ", e)
 
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

class ipcamCapture:
    def __init__(self, URL):
        self.Frame = []
        self.URL = URL
        self.status = False
        self.isstop = False		
	# 摄影机连接。
        self.capture = cv2.VideoCapture(URL) 
    def start(self):
	# 把程序放进子线程，daemon=True 表示该线程会随着主线程关闭而关闭。
        print('ipcam started!')
        threading.Thread(target=self.queryframe, daemon=True, args=()).start() 
    def stop(self):
	# 记得要设计停止无限循环的开关。
        self.isstop = True
        print('ipcam stopped!')   
    def getframe(self):
	# 当有需要影像时，再回传最新的影像。
        return self.Frame        
    def queryframe(self):
        while (not self.isstop):
            self.status, self.Frame = self.capture.read()            
        self.capture.release() 

def image_infer(source_id):#讀影像放入multi pool
    global left,right
    i= source_id
    try:
        frame = ipcams[i].getframe()                
        recent_Frames[i].append(frame) #最终结果放入缓存队列
    except Exception as e:
        Nosignal_wall_paper = np.zeros((400,720,3))
        cv2.putText(Nosignal_wall_paper,'NOSIGNALS',(100,250),cv2.FONT_HERSHEY_SIMPLEX,3,(0,255,0),15)
        recent_Frames[i].append(Nosignal_wall_paper)
        print(e.__traceback__.tb_lineno,e.args)               
stop=False
def multi_infer(source_ids):#從multi pool拿出圖片的地方
    pool = ThreadPool(8)
    global stop
    global dict_upload
    while True:
        pool.map(image_infer,source_ids)               
        # 多线程无法在内部show图像,所以把图像结果缓存到队列中,最后一起显示
        for i,Frame in enumerate(recent_Frames):
            k=cv2.waitKey(1)
            try:
                #print(dict_upload)
                if(dict_upload==False):
                    labels,xmin,xmax,ymin,ymax,conf=score_frame(Frame[0]) # Score the Frame -->辨識and傳回目標的方位
                    detect_n_warming(labels,conf,Frame[0])
                else:                    
                    cv2.imshow('source{}'.format(i),cv2.resize(Frame[0],(720,400),interpolation=cv2.INTER_AREA)) #队列第一帧#,(720,400)
            except Exception as e:   
                Nosignal_wall_paper = np.zeros((400,720,3))
                cv2.putText(Nosignal_wall_paper,'NOSIGNALS',(100,250),cv2.FONT_HERSHEY_SIMPLEX,3,(0,255,0),15)  
                cv2.imshow('source{}'.format(i),cv2.resize(Nosignal_wall_paper,(720,400),interpolation=cv2.INTER_AREA))#队列第一帧#,(720,400)          
             
            if k & 0xFF == ord('q'):
                stop=True
                break
        if stop==True :
            break
    cv2.destroyAllWindows()

#刷新偵測狀態，可以重新識別異常狀況
def refresh_statement():
    global dict_upload  
    dict_upload=False
count=0
#偵測到異常狀況 並上傳到資料庫
from threading import Timer
def detect_n_warming(labels,conf,frame):
    global Mail
    n = len(labels)
    global dict_upload
    global Greenhouse_id
    global starttime
    str={}#統計出現幾個人
    for i in range(n):   
        if conf[i] < 0.2: 
            continue 
        if((labels[i]=='person' or labels[i]=='cat' or labels[i]=='dog') and conf[i]>=0.6 ):###在這邊加上害蟲判斷
            str[labels[i]]=str.get(labels[i],0)+1 
    _str=''
    jj=0
    for i in str.keys():
        _str+=' and '+f"appearance {str[i]} {i}"if jj>0 else f"appearance {str[i]} {i}"
        jj+=1
    #print(_str)
    if( _str!=""):
        mysql=Sql(Server,DBUsername,DBPassword,Db,DBTableName)                       
        currTime = datetime.utcnow()#取得現在時間                
        unix_time = calendar.timegm(currTime.utctimetuple())#將現在的時間轉換成unix time    
        sqlstr=f"SELECT token FROM `line_notify` WHERE `email`='{Mail}'"
        results=mysql.inquery(sqlstr)
        #print(results)  
        #發送異常狀況給所有訂閲該溫室的使用者啦齁
        
        for i in results:
            token=i[0]
            # print(f'token=====>{token}')
            line_notify(_str,token,frame)#message,token)
        sqlstr=f'INSERT INTO `abnormal_situation` (greenhouse_id,discovery_time,situation,start_time)  VALUES ("{Greenhouse_id}",{unix_time},"{_str}",{starttime});'
        results=mysql.insert(sqlstr)  
        dict_upload=True
        mail(_str)#發信提醒
         #定時間 時間到了變成False
        t = Timer(60.0,refresh_statement)#這邊放幾秒鐘 ,[labels[i]] 10min
        t.start()        
        mysql.close()
  
def score_frame(frame):
    global model    
    results =  model(frame)
    labels = results.pandas().xyxy[0]['name']#results.xyxyn[0][:, -1].numpy()
    xmin=results.pandas().xyxy[0]['xmin']# results.xyxyn[0][:, :-1].numpy()
    xmax=results.pandas().xyxy[0]['xmax']# results.xyxyn[0][:, :-1].numpy()    
    ymin=results.pandas().xyxy[0]['ymin']# results.xyxyn[0][:, :-1].numpy()
    ymax=results.pandas().xyxy[0]['ymax']# results.xyxyn[0][:, :-1].numpy()
    conf=results.pandas().xyxy[0]['confidence']
    return labels,xmin,xmax,ymin,ymax,conf
def plot_boxes(labels, frame,xmin,xmax,ymin,ymax,conf):#看要不要框出來
    n = len(labels)
    x_shape, y_shape = frame.shape[1], frame.shape[0]
    for i in range(n):         
        # If score is less than 0.2 we avoid making a prediction.q
        if conf[i] < 0.2: 
            continue  
        x1 =xmin[i] #*x_shape#int(row[0]*x_shape)
        y1 =ymin[i]#*y_shape #int(row[1]*y_shape)
        x2 = xmax[i]#*x_shape#int(row[2]*x_shape)
        y2 = ymax[i]#*y_shape#int(row[3]*y_shape)
        bgr = (0, 255, 0) # color of the box
        #print(x1,x2,y1,y2)
        #classes =model.names # Get the name of label index
        label_font = cv2.FONT_HERSHEY_SIMPLEX #Font for the label.
        if((labels[i]=='person' or labels[i]=='cat' or labels[i]=='dog') and conf[i]>=0.6 ):
            #偵測到該畫面上有人類或是動物##這裏再確定動物            
            print(f"find {labels[i]},accuracy={conf[i]}")
            cv2.rectangle(frame, (int(x1), int(y1)), (int(x2), int(y2)),bgr, 4) #Plot the boxes
            cv2.putText(frame,\
                        labels[i], \
                        (int(x1), int(y1)), \
                        label_font, 0.9, bgr, 2) #Put a label over box.
    return frame
if __name__ == "__main__":   
    URLS = [
    #"rtsp://192.168.137.36:554/mjpeg/1",
    #"rtsp://192.168.137.239:554/mjpeg/1"  
'C:\\Users\\y1595\\Pictures\\[BTCLOD.COM] 全台最多校貓的國中100+大開箱!10隻以上品種貓等待領養!品種貓有時身世比米克斯淒慘_【許伯簡芝】【公益】-1080p60.mp4',
'C:\\Users\\y1595\\Pictures\\merge.mp4'
    #"rtsp://**********"    
    ] #rtsp地址列表q
    mysql=Sql(Server,DBUsername,DBPassword,Db,DBTableName)  
   
    #取得目前溫室内種植的植物和開始種植時間的資料
    sqlstr=f"SELECT start_time,plant_id FROM `greenhouse_plant` WHERE `greenhouse_id`='{Greenhouse_id}' and `end_time`=0"
    results=mysql.inquery(sqlstr)     
    #資料處理
    starttime=results[0][0]#當前種植作物的開始時間    
    sqlstr=f"SELECT mail FROM `usergreenhouse` WHERE `greenhouse_Id`='{Greenhouse_id}'"
    results=mysql.inquery(sqlstr)    
    Mail=results[0][0]
    print('user mail is ====>',Mail)
    mysql.close()
    ipcams =[] #摄像机对象列表
    recent_Frames= [deque(maxlen=15) for _ in range(len(URLS))]#存放结果图像的队列
    source_ids = list(range(len(URLS))) #rtsp源编号
    for URL in URLS:
        # 连接摄影机       
        ipc = ipcamCapture(URL)
        ipcams.append(ipc)
    for  ipcam in  ipcams:
        # 启动子线程
        ipcam.start()
    time.sleep(1) #要看load rtsp的時間
    multi_infer(source_ids)
   