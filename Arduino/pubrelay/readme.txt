/*
 * Project:AI_farmer
 * Author:yang
 * Support:a108222027@mail.shu.edu.tw
 * ===============================================================================
 *environment:Arduino UNO3 , ESP01s ,GY-39 , CJMCU-8128 ,Gravity 模拟防水土壤湿度传感器, relay*3 , Fan , Bump , Light 
 * ===============================================================================
 * 脚位：
 * ESP01s:
 * VCC,CH_PD <-->   3.3v
 * GND       <-->   GND
 * URXD      <-->   RX(2)
 * UTXD      <-->   TX(~3)
 * --------------------------------------------------
 * GY-39:
 * VCC       <-->   3.3v~5.5v
 * GND       <-->   GND
 * SCL       <-->   SCL(A5)
 * SDA       <-->   SDA(A4)
 * notice: 
 * S1=0（接 GND 时） 仅芯片模式, Pin7 为芯片 SCL 总线, Pin8 为芯片 SDA 总线
 * 
 *        s0(EN) s1(GND)
 *        -----------
 * vcc(1) |         |SCL(8)
 * pin2   |         |SDA(7)
 * pin3   |         |pin6
 * gnd(4) |  GY-39  |pin5
 *        -----------
 * --------------------------------------------------
 * CJMCU-8128:
 * VCC       <-->   3.3v
 * GND       <-->   GND
 * SDA       <-->   SDA(A4)
 * SCL       <-->   SCL(A5)
 * --------------------------------------------------
 * Gravity 模拟防水土壤湿度传感器:
 * 红色线 <-->  VCC
 * 黑色线 <-->  GND
 * 黄色线 <-->  A0
 * 屏蔽线 <-->  GND
 * 工作电压： 3.3 ~ 5.5 VDC(here we use 5V)
 * 工作电流： 7mA
 * 输出电压： 0 ~ 2.9 VDC
 * --------------------------------------------------
 * relay:
 * Normal OPEN:觸發前NO與COM不導通，觸發后兩脚導通
 * COM                    <-->   AC/DC 供電端 正極
 * Normal Open            <-->   電機正極(風扇/Bump/etc)
 * 電機正極(風扇/Bump/etc)  <-->   AC/DC 供電端 負極
 * 
 * 5V           <-->   Arduino 5V
 * GND          <-->   GND
 * IN           <-->   ardunio serial pin
 * --------------------------------------------------
 * ===============================================================================
 * relay-Mode:
 * 1.automatic
 * 2.handMode 
 * automatic：
 * （一）原始設定：一開始會有原廠設定： 相關會觸發relay的溫濕度會先固定一個值寫死
 * （二）收到訂閲：隨著訂閲的訊息，從知識庫獲取當前作物資料，根據該資料進行智慧的環控
 * handMode:
 * 收到訂閲資料，直接觸發relay進行控制
 * ===============================================================================
 * Library:
 * 1.PubSubClient.h
 * !!Caution:  
 *      The callback function header needs to be declared before the PubSubClient constructor 
        and the actual callback defined afterwards.This ensures the client reference in the callback function is valid.
 * 2.WiFiEspClient.h
 * 3.WiFiEsp.h
 * 4.SoftwareSerial.h
 * 5.Wire.h
 * 6.Adafruit_Sensor.h
 * 7.Max44009.h
 * 8.Adafruit_Si7021.h
 * 9.SparkFunCCS811.h
 *  ===============================================================================
	//------------------發佈訂閲-------------------------------
	const char* PubTopic="ai_farmer/sensor";
	//------------------訂閲消息--------------------------------
   const char* SubTopic = "ai_farmer/control";
 *  Subscribe  Parameter  Intro 
 *  Subscribe Topic: "ai_farmer/control" 
 *  datasource: integer   
 *  接受到的topic值為三位數的int,利用truth table去看收到的subscribe需要做出什麽對應的動作
 * Truth Table:
 *Fan  water  light
 * 0     0      0    全關 
 * 0     0      1    開燈
 * 0     1      0    澆水
 * 0     1      1    澆水&開燈
 * 1     0      0    開風扇
 * 1     0      1    開風扇&開燈
 * 1     1      0    開風扇&澆水
 * 1     1      1    全做
 *  ===============================================================================
 * define relay pinMode &relay function
 * pin7:風扇
 * pin8:抽水馬達bump
 * pin9:燈光
 * ===============================================================================
*/