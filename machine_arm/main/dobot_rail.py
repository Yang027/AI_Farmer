import DobotDllType as dType

CON_STR = {
        dType.DobotConnect.DobotConnect_NoError: "DobotConnect_NoError",
        dType.DobotConnect.DobotConnect_NotFound: "DobotConnect_NotFound",
        dType.DobotConnect.DobotConnect_Occupied: "DobotConnect_Occupied"}
api = dType.load()
# Connect Dobot
dType.DisconnectDobot(api)
state = dType.ConnectDobot(api, "", 115200)[0]
print("Connect status:", CON_STR[state])
if (state == dType.DobotConnect.DobotConnect_NoError):
    try:
        dType.SetDeviceWithL(api, 1)
    except:
        pass
        
        