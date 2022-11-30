/*
   Project:AI_farmer
   Author:yang
   Support:a108222027@mail.shu.edu.tw
   ===============================================================================
  environment:Arduino UNO3 , ESP01s ,GY-39 , CJMCU-8128 ,Gravity 模拟防水土壤湿度传感器, relay*3 , Fan , Bump , Light
   ===============================================================================
   脚位：
   ESP01s:
   VCC,CH_PD <-->   3.3v
   GND       <-->   GND
   URXD      <-->   RX(2)
   UTXD      <-->   TX(~3)
   --------------------------------------------------
   GY-39:
   VCC       <-->   3.3v~5.5v
   GND       <-->   GND
   SCL       <-->   SCL(A5)
   SDA       <-->   SDA(A4)
   notice:
   S1=0（接 GND 时） 仅芯片模式, Pin7 为芯片 SCL 总线, Pin8 为芯片 SDA 总线

          s0(EN) s1(GND)
          -----------
   vcc(1) |         |SCL(8)
   pin2   |         |SDA(7)
   pin3   |         |pin6
   gnd(4) |  GY-39  |pin5
          -----------
   --------------------------------------------------
   CJMCU-8128:
   VCC       <-->   3.3v
   GND       <-->   GND
   SDA       <-->   SDA(A4)
   SCL       <-->   SCL(A5)
   --------------------------------------------------101111115
   Gravity 模拟防水土壤湿度传感器:
   红色线 <-->  VCC
   黑色线 <-->  GND
   黄色线 <-->  A0
   屏蔽线 <-->  GND
   工作电压： 3.3 ~ 5.5 VDC(here we use 5V)
   工作电流： 7mA
   输出电压： 0 ~ 2.9 VDC
   --------------------------------------------------
   relay:
   Normal OPEN:觸發前NO與COM不導通，觸發后兩脚導通
   COM                    <-->   AC/DC 供電端 正極
   Normal Open            <-->   電機正極(風扇/Bump/etc)
   電機正極(風扇/Bump/etc)  <-->   AC/DC 供電端 負極

   5V           <-->   Arduino 5V
   GND          <-->   GND
   IN           <-->   ardunio serial pin
   --------------------------------------------------
   加熱瓦片：
   12v 4~12W
   adapter need:12v~1A
   ===============================================================================
   relay-Mode:
   1.automatic
   2.handMode
   automatic：
   （一）原始設定：一開始會有原廠設定： 相關會觸發relay的溫濕度會先固定一個值寫死
   （二）收到訂閲：隨著訂閲的訊息，從知識庫獲取當前作物資料，根據該資料進行智慧的環控
   handMode:
   收到訂閲資料，直接觸發relay進行控制
   ===============================================================================
   Library:
   1.PubSubClient.h
   !!Caution:
        The callback function header needs to be declared before the PubSubClient constructor
        and the actual callback defined afterwards.This ensures the client reference in the callback function is valid.
   2.WiFiEspClient.h
   3.WiFiEsp.h
   4.SoftwareSerial.h
   5.Wire.h
   6.Adafruit_Sensor.h
   7.Max44009.h
   8.Adafruit_Si7021.h
   9.SparkFunCCS811.h
    ===============================================================================
  //------------------發佈訂閲-------------------------------
  const char* PubTopic=greenhouse_ID+"/sensor";
  //------------------訂閲消息--------------------------------
  const char* SubTopic = greenhouse_ID+"/control";<====relay的動作
  const char* SubTopic1 =greenhouse_ID+"/info";   <====回傳即時的

    Subscribe Topic: greenhouse_ID+"/control"
    datasource: integer
    接受到的topic值為三位數的int,利用truth table去看收到的subscribe需要做出什麽對應的動作
   Truth Table:
  hot  water  light  Fan
   0     0      0     0   全關                      --1
   0     0      0     1   開風扇                     --2
   0     0      1     0   開燈                      --3
   0     0      1     1   澆水&開風扇                --4
   0     1      0     0   澆水                      --5
   0     1      0     1   開風扇&水                  --6
   0     1      1     0   澆水&開燈                  --7
   0     1      1     1   開燈&開風扇&澆水            --8
   1     0      0     0   開除濕                     --9
   1     0      0     1   開除濕&開風扇               --10
   1     0      1     0   開除濕&燈                  --11
   1     0      1     1   開除濕&燈&風扇              --12
   1     1      0     0   開除濕&澆水                --13
   1     1      0     1   開除濕&澆水&風扇            --14
   1     1      1     0   開除濕&澆水&開燈            --15
   1     1      1     1   全開                      --16
    ===============================================================================
   define relay pinMode &relay function
   pin7:風扇
   pin8:抽水馬達bump
   pin9:燈光
   pin10:除濕
   ===============================================================================
*/

const char* Greenhouse_id = "ai_farmer";
//-------------import library--------------------
#include <PubSubClient.h>
#include <WiFiEspClient.h>
#include <WiFiEsp.h>          //import wifi library
#include "SoftwareSerial.h"   //import UNO serial port library
//#include "arduino_sercrets.h" //import SSSID&password
#include <Wire.h>
#include <Adafruit_Sensor.h>
#include "Max44009.h"
#include "Adafruit_Si7021.h"
#include "SparkFunCCS811.h"
//-------------end import library-----------------
long Time_Send = 20000; // ms
//-------------網路參數宣告和設定--------------------
#define MSG_BUFFER_SIZE  (100)                                   //publish topic buffer length
char WIFI_AP[] =   "ssid";                                    //AP ssid
char WIFI_PASSWORD[] =  "password";                              //AP PASSWORD
char MqttServer[] = "mqttserver"; //mqtthost                           //MQTT Broker IP address
SoftwareSerial soft(3, 2); //  TX, RX                            //ESP8266  TX, RX

// 初始化乙太網客戶端物件 -- WiFiEspClient.h
WiFiEspClient espClient;                                        //initialize WIFI library
// 初始化MQTT庫PubSubClient.h的物件
//PubSubClient client(MqttServer, 1883, callback, espClient);     //initialize MQTT library
PubSubClient client(MqttServer, 1883, espClient);     //initialize MQTT library
char msg[MSG_BUFFER_SIZE];                                      //publish topic buffer length
int status = WL_IDLE_STATUS;
unsigned long lastSend;
//送出時間 去計算多久publish一次
const int AirValue = 605;   //you need to change this value that you had recorded in the air
const int WaterValue = 90;  //you need to change this value that you had recorded in the water

int intervals = (AirValue - WaterValue);
float soilMoistureValue = 0;
//-----------------宣告sensor-------------------------
Max44009 myLux(0x4A);
//#define CCS811_ADDR 0x4B //Alternate I2C Address
CCS811 mySensor(0x5A);
Adafruit_Si7021 sensor = Adafruit_Si7021();

//------------------發佈訂閲-------------------------------
const char* PubTopic = "gh_abner_yang_01/sensor";
//------------------訂閲消息-------------------------------
const char* SubTopic = "gh_abner_yang_01/control";
//-------define relay pinMode &relay function-------------

//---------------------------------------------------------------
void setup() {
  initalsensor();
  Serial.begin(115200);
  InitWiFi();  // 連線WiFi
  lastSend = 0;
  MQTTconnect(); // 連線MQRTT
}
void initalsensor() {
  Wire.begin();
  myLux.setContinuousMode();
  mySensor.begin();
  sensor.begin();
}
void loop() {
//  if (!client.connected()) {
//    MQTTconnect();
//  }
  unsigned long now = millis();
  if (now - lastSend > 60000) {
    lastSend = now;
    publishinfo();
  }//end if
  //delay(10000);
  client.loop();
}//end setup
void publishinfo()
{
  if (mySensor.dataAvailable())
  {
    //這裏是讀Co2 & humid & temp
    mySensor.readAlgorithmResults();
    soilMoistureValue = analogRead(A0);  //put Sensor insert into soil
    //發送出去的topic格式: 溫度，濕度，二氧化碳ppm,lux,soil mosit
    float soilv = 100 - ((soilMoistureValue / intervals) * 100.0);
    String _sendmsg = gettemp() + "," + gethumid() + "," + getco2() + "," + getlux() + "," + String(soilv, 2);
    char* sendmsg = _sendmsg.c_str();
    snprintf (msg, MSG_BUFFER_SIZE, sendmsg);
    //將msg發出去
    client.publish(PubTopic, msg);
  }
}
String getlux()
{
  float lux =  myLux.getLux();
  String ll = String(lux , 2);
  return ll;
}

String gethumid()
{
  float humid = sensor.readHumidity();
  String hum = String(humid, 2);
  return hum;
}
String gettemp()
{
  float temp = sensor.readTemperature();
  String tmp = String( temp, 2);
  return tmp;
}
String getco2()
{
  float co2 = mySensor.getCO2();
  String _co2 = String(co2, 2);
  return _co2;
}
void InitWiFi()
{
  boolean _status = false;
  while (_status == false) {
    // 初始化軟串列埠，軟串列埠連線ESP模組
    soft.begin(9600);
    // 初始化ESP模組
    WiFi.init(&soft);
    if (WiFi.status() == WL_NO_SHIELD)
      Serial.println("WiFi shield not present");
    else _status = true;
  }
  Serial.println("[InitWiFi]Connecting to AP ...");
  // 嘗試連線WiFi網路
  while ( status != WL_CONNECTED) {
    Serial.print("[InitWiFi]Attempting to connect to WPA SSID: ");
    Serial.println(WIFI_AP);
    // Connect to WPA/WPA2 network
    status = WiFi.begin(WIFI_AP, WIFI_PASSWORD);
    delay(500);
  }
  //WiFi.config();
  Serial.println("[InitWiFi]Connected to AP");
}
// 嘗試連線connect是個過載函式 (clientId, username, password)
void MQTTconnect() {
  // 一直迴圈直到連線上MQTT伺服器
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    if (client.connect("ai_farmer/01561623", "yang", "1234")) {
      Serial.println("connected");
      // Once connected, publish an announcement...
      //char* _sendmsg = "send MQTT Connect";
      //snprintf (msg, MSG_BUFFER_SIZE, _sendmsg);
      //client.publish(PubTopic, msg);
    }
    else {
      delay(15000);
    }
  }
}
