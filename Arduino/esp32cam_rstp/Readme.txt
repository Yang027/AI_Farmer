[usb to ttl]
red 	-> 5v
black -> gnd
white -> gpio1
green -> gpio3

[Prepare]
-----------------------------------------------------------------------------
1.ADD:
	Arduino >File>preferences>addition boards manager URL:
		https://dl.espressif.com/dl/package_esp32_index.json
	>OK
	
2.Tools>Board>Boards Manager
	Search:esp32,and install it 
	
3.Tools>Board> ---->  <ESP32 Wrover Module>
  Then select port 
  partition scheme>huge app
  
[Upload]  
-----------------------------------------------------------------------------
1.open serial window,and check the baud:115200
2.then let 'IO0' and 'GND' 短路 and push the reset button
3.there will be some line appear to Serials window:waiting for download
4.upload ur code
5.after uploading,Arduino ide will tell u to 'resetting via RTS pin',then push reset button again


notice:IO0和gnd短路的時候按reset是下載，也就是告訴esp32cam我們要上傳code，所以上傳完要reset的時候我們要拔掉短路的那條綫。




-----------------------------------------------------------------------------------------------------------------------------------
python:
use mutli-thread to get the rstp source