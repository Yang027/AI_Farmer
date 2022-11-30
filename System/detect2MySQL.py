import numpy as np
import torch
import math
import os, sys,cv2
import time
import sqlalchemy
modelPath = os.path.join(".","model")
import csv, json, sqlite3,json
import pandas as pd
import MySQLdb
import paho.mqtt.client as mqtt
global result
Server_IP="192.192.156.30"
Port=1883
Alivetime=60
MQTTUsername='yang'
MQTTPassword='1234'
camera_IP=""
publish_message = 'python3 main_debug_v3_with_dobot.py'
dobot_message = 'python3 dobot_rail.py'
Db='aifarmer'
DBUsername='aifarmer'
DBPassword='1234'
DBTableName='plant'
class Sql:
    # 初始化，連接資料庫
    def __init__(self, _server, _username, _password, db, dbTable):
        self.db = MySQLdb.connect(_server, _username, _password, db, charset='utf8')
        self.cursor = self.db.cursor()
        self.table = dbTable
        print("connect success")

        # 查詢

    def inquery(self, sqlstr):
        sql = sqlstr  # SQL 查詢語句
        try:
            self.cursor.execute(sql)  # 執行SQL指令
            results = self.cursor.fetchall()  # 獲取運行結果
            return results
        except:
            print("Error: unable to fetch data")

    # 插入
    def insert(self, sqlstr):
        sql = sqlstr
        try:
            self.cursor.execute(sql)
            self.db.commit()
        except Exception as e:
            print(e, "Error: unable to insert data")

    # 關閉連綫
    def close(self):
        self.db.close()
def camera():
    cap = cv2.VideoCapture(camera_IP)
    ret, frame = cap.read()
    RGB = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    # cv2.imshow('live', RGB)
    cap.release()
    cv2.destroyAllWindows()
    return RGB


if __name__ == "__main__":
    plant={'番茄':'tomato'}
    # stage = torch.hub.load('ultralytics/yolov5', 'custom', f'{modelPath}/stage/plant')
    database= Sql('192.192.156.30','aifarmer','1234','aifarmer','greenhouse_plant')
    gh_list = database.inquery("SELECT greenhouse_id,start_time FROM greenhouse_plant WHERE end_time = 0")
    gh_id = [i for i in gh_list]
    print('所有運行溫室',gh_id)

    for gh in gh_id:
        sqlstr = f"SELECT plant_id FROM greenhouse_plant WHERE `greenhouse_id`='{gh[0]}' and `start_time`='{gh[1]}'"
        plant_name=database.inquery(sqlstr)[0][0]
        # print(gh[0],gh[1])
        sqlstr =f"SELECT growth_cycle FROM growth_cycle WHERE `greenhouse_id`='{gh[0]}' and `start_time`='{gh[1]}' ORDER BY `growth_cycle`.`record_time` DESC"
        growth_circle=database.inquery(sqlstr)[0][0]
        print('當前生長階段',growth_circle)
        if plant_name in plant:
            if int(growth_circle) != 4:
                stage = torch.hub.load('ultralytics/yolov5', 'custom', path=f'{modelPath}/stage/{plant[plant_name]}.pt')
                stage.conf=0.7
                count =0
                while True:
                    results = stage(camera()) # 從webcam抓
                    if results.pandas().xyxy[0].values.any():
                        break
                    else:
                        if count <31:
                            print(count)
                            count+=1
                        else:
                            break
                # results.show()
                boxs = results.pandas().xyxy[0].values
                record_time = math.floor(time.time())
                if boxs.any():
                    print(record_time)
                    fructify_boxs=boxs[np.where(boxs == 'fructify')[0]]
                    label = 4 if fructify_boxs.any() else 3
                    if label > int(growth_circle):
                        sqlstr = f"INSERT INTO growth_cycle (greenhouse_id, record_time, growth_cycle, start_time) VALUES ('{gh[0]}', '{record_time}','{label}',{gh[1]})"
                        database.insert(sqlstr)
                else:
                    if int(growth_circle) < 2:
                        sqlstr = f"INSERT INTO growth_cycle (greenhouse_id, record_time, growth_cycle, start_time) VALUES ('{gh[0]}', '{record_time}','{2}',{gh[1]})"
                        database.insert(sqlstr)
            else:
                mature = torch.hub.load('ultralytics/yolov5', 'custom', path=f'{modelPath}/mature/{plant[plant_name]}.pt')
                mature.conf = 0.7
                count = 0
                while True:
                    results = mature(camera())  # 從webcam抓
                    if results.pandas().xyxy[0].values.any():
                        break
                    else:
                        print(count)
                        if count < 31:
                            count += 1
                        else:
                            break
                boxs = results.pandas().xyxy[0].values
                mature_boxs = boxs[np.where(boxs == f'mature_{plant[plant_name]}')[0]]
                if mature_boxs.any():
                    print('call robot arm')
                    client = mqtt.Client()

                    # 設定登入帳號密碼
                    client.username_pw_set(MQTTUsername, MQTTPassword)

                    # 設定連線資訊(IP, Port, 連線時間)+
                    client.connect(Server_IP, Port, Alivetime)
                    # 運行robot arm
                    client.publish("robot/arm", dobot_message)
                    client.publish("robot/arm", publish_message)
    database.close()



