import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

def mail():
    content = MIMEMultipart()  #建立MIMEMultipart物件
    content["subject"] = "溫室異常狀況提醒"  #郵件標題
    content["from"] = "aifarmer2022@gmail.com"  #寄件者
    content["to"] = "a108222027@mail.shu.edu.tw" #收件者
    content.attach(MIMEText("親愛的使用者你好:\n 您的溫室有異常狀況，請您及時查看"))  #郵件內容
    with smtplib.SMTP(host="smtp.gmail.com", port="587") as smtp:  # 設定SMTP伺服器
        try:
            smtp.ehlo()  # 驗證SMTP伺服器
            smtp.starttls()  # 建立加密傳輸
            smtp.login("aifarmer2022@gmail.com", "lpjcuxsoaqbtuece")  # 登入寄件者gmail
            smtp.send_message(content)  # 寄送郵件
            print("Complete!")
        except Exception as e:
            print("Error message: ", e)
mail()