#include <WiFiEspClient.h>
#include <WiFiEsp.h>          //import wifi library
#include "SoftwareSerial.h"   //import UNO serial port library
#include <MQTT.h>
char WIFI_AP[] = "aifarmer";                                    //AP ssid
char WIFI_PASSWORD[] = "12345678";                               //AP PASSWORD
WiFiEspClient espClient;
int status = WL_IDLE_STATUS;
MQTTClient client;
const char* SubTopic = "gh_abner_yang_01/control";
//-------define relay pinMode &relay function-------------
const int FAN = 7;
const int BUMP = 8;
const int LIGHT = 9;
const int hot = 10;
bool _fan = false;
bool _bump = false;
bool _light = false;
bool _hot = false;
SoftwareSerial soft(3, 2); //  TX, RX   
void initalrelay()
{
  pinMode(FAN, OUTPUT);
  pinMode(BUMP, OUTPUT);
  pinMode(LIGHT, OUTPUT);
  pinMode(hot, OUTPUT);
  relay();
}
//relay action()
void connect() {
  Serial.print("checking wifi...");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(1000);
  }
  Serial.print("\nconnecting...");
  while (!client.connect("arduino", "yang", "1234")) {
    Serial.print(".");
    delay(1000);
  }
  Serial.println("\nconnected!");
  client.subscribe(SubTopic,2);
}

void messageReceived(String &topic, String &cc) {
  if  (cc.compareTo("of")==0){_fan=true;}
  else if (cc.compareTo("oh")==0){_hot=true;}
  else if(cc.compareTo("ob")==0){_bump=true;}
  else if(cc.compareTo("ol")==0){_light=true;}
  else if(cc.compareTo("cf")==0){_fan=false;}
  else if(cc.compareTo("ch")==0){_hot=false;}
  else if(cc.compareTo("cb")==0){_bump=false;}
  else if(cc.compareTo("cl")==0){_light=false;}
  else if (cc.compareTo( "0000")==0) { //  全關
    _fan = false;
    _bump = false;
    _light = false;
    _hot = false;
  }
  else if (cc.compareTo("0001")==0) { //開風扇
    _fan = true;
    _bump = false;
    _light = false;
    _hot = false;
    Serial.println("1");
  }
  else  if (cc.compareTo("0010")==0) { //開燈
    _fan = false;
    _bump = false;
    _light = true;
    _hot = false;
    //Serial.println("open");
  }
  else   if (cc.compareTo("0011")==0)  { // 開燈&開風扇
    _fan = true;
    _bump = false;
    _light = true;
    _hot = false;
  }
  else   if (cc.compareTo("0100")==0) { //澆水
    _fan = false;
    _bump = true;
    _light = false;
    _hot = false;
  }
  else   if (cc.compareTo("0101")==0) { //開風扇&水
    _fan = true;
    _bump = true;
    _light = false;
    _hot = false;
  }
  else   if (cc.compareTo("0110")==0) { //澆水&開燈
    _fan = false;
    _bump = true;
    _light = true;
    _hot = false;
  }
  else   if (cc.compareTo("0111")==0) { //開燈&開風扇&澆水
    _fan = true;
    _bump = true;
    _light = true;
    _hot = false;
  }
  else   if (cc.compareTo("1000")==0) { //開除濕
    _fan = false;
    _bump = false;
    _light = false;
    _hot = true;
  }
  else    if (cc.compareTo("1001")==0) { //開除濕&開風扇
    _fan = true;
    _bump = false;
    _light = false;
    _hot = true;
  }
  else    if (cc.compareTo("1010")==0) { //開除濕&燈
    _fan = false;
    _bump = false;
    _light = true;
    _hot = true;
  }
  else   if (cc.compareTo("1011")==0) { //開除濕&燈&風扇
    _fan = true;
    _bump = false;
    _light = true;
    _hot = true;
  }
  else   if (cc.compareTo("1100")==0) { //開除濕&澆水
    _fan = false;
    _bump = true;
    _light = false;
    _hot = true;
  }
  else   if (cc.compareTo("1101")==0) { //開除濕&澆水&風扇
    _fan = true;
    _bump = true;
    _light = false;
    _hot = true;
  }
  else   if (cc.compareTo( "1110")==0) { //開除濕&澆水&開燈
    _fan = false;
    _bump = true;
    _light = true;
    _hot = true;
  }
  else   if (cc.compareTo("1111")==0)  { //全開
    _fan = true;
    _bump = true;
    _light = true;
    _hot = true;
  }
  relay();
}

void setup() {
  Serial.begin(115200);
  InitWiFi();  // 連線WiFi
  client.begin("192.192.156.30", 1883, espClient);
  client.onMessage(messageReceived);
  initalrelay();
  connect();
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
void relay()
{
//  if(_fan)Serial.println("fan open");
//  if(_bump)Serial.println("bump open");
//  if(_light)Serial.println("light open");
//  if(_hot)Serial.println("hot open");
   if(_hot==true)
    _fan=true;
  digitalWrite(FAN, _fan == true ? LOW : HIGH); //open
  digitalWrite(BUMP, _bump == true ? LOW : HIGH); //open
  digitalWrite(LIGHT, _light == true ? LOW : HIGH); //open
  digitalWrite(hot, _hot == true ? LOW : HIGH); //open
  if(_bump==true)
  {
    delay(100);
    _bump=false;    
    digitalWrite(BUMP, _bump == true ? LOW : HIGH); //open
  }
}
void loop() {
  client.loop();
//  delay(250);
//  if (!client.connected()) {
//    Serial.println("reconnecting...");
//    connect();
//  }   
}
