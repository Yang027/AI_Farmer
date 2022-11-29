import pyrealsense2 as rs
import numpy as np
import cv2
import random
import torch 
import time
import math

import DobotDllType as dType
CON_STR = {
        dType.DobotConnect.DobotConnect_NoError: "DobotConnect_NoError",
        dType.DobotConnect.DobotConnect_NotFound: "DobotConnect_NotFound",
        dType.DobotConnect.DobotConnect_Occupied: "DobotConnect_Occupied"}
# model = torch.hub.load('ultralytics/yolov5', 'yolov5s')

FRAME_CENTER = np.array([343, 240])

HOME ={
    'X':0,
    'Y':-170,
    'Z':0,
    'R':0
}

STUDY_UP={
    'X': 255,
    'Y': 0,
    'Z': 80,
    'R': 90
}
def get_mid_pos(box,depth_data,randnum):
    distance_list = []
    mid_pos = [(box[0] + box[2])//2, (box[1] + box[3])//2] #確定索引深度的中心像素位置
    min_val = min(abs(box[2] - box[0]), abs(box[3] - box[1])) #確定深度搜索範圍
    for i in range(randnum):
        bias = random.randint(-min_val//4, min_val//4)
        dist = depth_data[int(mid_pos[1] + bias), int(mid_pos[0] + bias)]
        if dist:
            distance_list.append(dist)
    distance_list = np.array(distance_list)
    distance_list = np.sort(distance_list)[randnum//2-randnum//4:randnum//2+randnum//4] # 冒泡排序+中值濾波
    return np.mean(distance_list)

def get_center_distance(mature_boxs):
    distance_boxs = []
    for box in mature_boxs:
        box_center = [int(box[0] + box[2]) // 2, int(box[1])]  # 抓出物體中心
        box_gap = box_center - FRAME_CENTER
        box = box.tolist()
        box.append(math.hypot(box_gap[0],box_gap[1]))
        distance_boxs.append(box)
    return np.array(distance_boxs)

def get_center_distance_near(mature_boxs):
    length = float('inf')
    index=0
    for i in range(len(mature_boxs)):
        if float(mature_boxs[i,-1]) < length:
            length = float(mature_boxs[i,-1])
            index=i
    return index

def dectshow(org_img, boxs):
    img = org_img.copy()
    try:
        if boxs.size > 0:
            dis_list=get_center_distance(boxs)
            index = get_center_distance_near(dis_list)
            box = boxs[index,:]
        # for box in boxs:
            cv2.rectangle(img, (int(box[0]), int(box[1])), (int(box[2]), int(box[3])), (0, 255, 0), 2)
        # dist = get_mid_pos(box, depth_data, 24)
        # cv2.putText(img, box[-1] + str(dist / 10)[:4] + 'm',
        #             (int(box[0]), int(box[1])), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 0), 2)
        cv2.imshow('dec_img', img)
        key = cv2.waitKey(1)
            # Press esc or 'q' to close the image window
        if key & 0xFF == ord('q') or key == 27:
            cv2.destroyAllWindows()
            return
    except Exception as ex:
        print(ex)

def Rail(pipeline,alignedFs,state,api,L_bias):
    if (state == dType.DobotConnect.DobotConnect_NoError):
        dType.SetQueuedCmdClear(api)
        # dType.SetDeviceWithL(api, 1)
        dType.SetPTPLParams(api, 100, 100, isQueued=1)
        L_ori=dType.GetPoseL(api)[0]
        # dType.SetPTPLParams(api, 50, 50, isQueued=1)
        dType.SetPTPWithLCmd(api, 1, HOME['X'], HOME['Y'], HOME['Z'], HOME['R'], L_ori+L_bias, isQueued=1)
        wait_move = dType.SetWAITCmd(api, 0, isQueued=1)[0]
        dType.SetQueuedCmdStartExec(api)
        # print(wait_move,dType.GetQueuedCmdCurrentIndex(api)[0])
        while wait_move > dType.GetQueuedCmdCurrentIndex(api)[0]:
            camera(pipeline, alignedFs)
            dType.dSleep(100)
        dType.SetQueuedCmdStopExec(api)
        # return L_ori
def arm_Z(pipeline,alignedFs,state,api,Z_bias,X_bias):
    camera(pipeline,alignedFs)
    if (state == dType.DobotConnect.DobotConnect_NoError):
        dType.SetQueuedCmdClear(api)
        dType.SetPTPCmd(api, 1,120, 0, 0,90, isQueued=1)
        dType.SetEndEffectorGripper(api, 1, 0, isQueued=1)

        dType.SetPTPCmd(api, 1, STUDY_UP['X']+X_bias, STUDY_UP['Y'], STUDY_UP['Z']+Z_bias, STUDY_UP['R'], isQueued=1)
        dType.SetEndEffectorGripper(api, 1, 1, isQueued=1)
        dType.SetWAITCmd(api, 1, isQueued=1)
        dType.SetPTPCmd(api, 1, 150, 0, 0, 90, isQueued=1)
        dType.SetPTPWithLCmd(api, 1, 150, 0, 0, 90, 0, isQueued=1)
        dType.SetPTPCmd(api, 1,66, 230, -70,-16, isQueued=1)
        dType.SetEndEffectorGripper(api, 1, 0, isQueued=1)
        dType.SetWAITCmd(api, 1, isQueued=1)
        dType.SetEndEffectorGripper(api, 1, 1, isQueued=1)
        dType.SetWAITCmd(api, 1, isQueued=1)
        dType.SetEndEffectorGripper(api, 0, 0, isQueued=1)
        dType.SetPTPCmd(api, 1, HOME['X'], HOME['Y'], HOME['Z'], HOME['R'], isQueued=1)
        wait_move = dType.SetWAITCmd(api, 0, isQueued=1)[0]
        dType.SetQueuedCmdStartExec(api)
        # print(wait_move,dType.GetQueuedCmdCurrentIndex(api)[0])
        while wait_move > dType.GetQueuedCmdCurrentIndex(api)[0]:
            camera(pipeline,alignedFs)
            dType.dSleep(100)
        dType.SetQueuedCmdStopExec(api)

def camera(pipeline,alignedFs):
    fs = pipeline.wait_for_frames()
    aligned_frames = alignedFs.process(fs)

    color_frame = aligned_frames.get_color_frame()
    depth_frame = aligned_frames.get_depth_frame()
    if not depth_frame or not color_frame:
        return
    # Convert images to numpy arrays
    depth_image = np.asanyarray(depth_frame.get_data())
    color_image = np.asanyarray(color_frame.get_data())
    color_image = cv2.cvtColor(color_image, cv2.COLOR_BGR2RGB)
    results = model(color_image)
    boxs = results.pandas().xyxy[0].values
    color_image = cv2.cvtColor(color_image, cv2.COLOR_RGB2BGR)
    dectshow(color_image,boxs)
    return [color_image,depth_image,boxs]


def run(pipeline,alignedFs,state,api,model):
    try:
        while True:
            # Wait for a coherent pair of frames: depth and color
            # fs = pipeline.wait_for_frames()
            # aligned_frames = alignedFs.process(fs)
            #
            # color_frame = aligned_frames.get_color_frame()
            # depth_frame = aligned_frames.get_depth_frame()
            # if not depth_frame or not color_frame:
            #     continue
            # # Convert images to numpy arrays
            # depth_image = np.asanyarray(depth_frame.get_data())
            # color_image = np.asanyarray(color_frame.get_data())
            # color_image = cv2.cvtColor(color_image, cv2.COLOR_BGR2RGB)


            img= camera(pipeline,alignedFs)
            if not img:
                continue
            else:
                color_image = img[0]
                depth_image = img[1]
                boxs = img[2]

            # results = model(color_image)
            # boxs= results.pandas().xyxy[0].values
            print('total object',boxs)
            print(1)
            # 1.判斷是否有成熟的
            mature_boxs = boxs[np.where(boxs == 'mature_tomato')[0]]
            color_image = cv2.cvtColor(color_image, cv2.COLOR_RGB2BGR)
            if mature_boxs.any():
                unpickable = []
                # 判斷是否在手臂能夠抓取的距離內
                for i in range(len(mature_boxs)):
                    dist = get_mid_pos( mature_boxs[i], depth_image, 24)
                    X_can_pick_max_distance = (boxs[i][1] / 19.28571428571429) + 29 if boxs[i][1] <= 135 else 38 - ((boxs[i][1] - 135) / 21.85714285714286)
                    if (dist/10) > X_can_pick_max_distance or np.isnan(dist) or mature_boxs[i][1] > 290:
                        unpickable.append(i)
                print("unpickable index -->", unpickable)

                boxs = np.delete(mature_boxs, unpickable, axis=0)
                print('can pick boxs -->',boxs)
                if boxs.any():
                # 2.計算成熟番茄與中心點的直線距離
                    dis_list = get_center_distance(boxs)
                    # 2.1 找出離中心最近的物體
                    index = get_center_distance_near(dis_list)
                    box = boxs[index, :]
                    # print("最近的物體 -->",box)
                    # 3.判斷物體距離
                    dist = get_mid_pos( box, depth_image, 24)
                    # dectshow(color_image, boxs, depth_image)
                    cv2.rectangle(color_image, (int(box[0]), int(box[1])), (int(box[2]), int(box[3])), (0, 255, 0), 2)
                    cv2.putText(color_image, box[-1] + str(dist / 10)[:4] + 'm',
                                (int(box[0]), int(box[1])), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 0), 2)

                    L_bias = ((float(box[2])+float(box[0]))/2 - 343)

                    if L_bias > 5 or L_bias < -5:
                        Rail(pipeline,alignedFs,state,api,L_bias)
                    else:
                        Z_bias = 0 - float(box[1])*0.60
                        X_can_pick_max_distance = (box[1]/ 19.28571428571429)+29 if box[1] <= 135 else 38-((box[1]-135) / 21.85714285714286)
                        print('最遠抓取距離-->',X_can_pick_max_distance)
                        X_bias = (dist - STUDY_UP['X']*1.1375)*1.15
                        print('距離-->',dist/10)
                        print('往前的數值',X_bias)
                        print('box high',box[1])
                        arm_Z(pipeline,alignedFs,state,api,Z_bias,X_bias)
                else:
                    L_ori = dType.GetPoseL(api)[0]
                    if L_ori == 1000:
                        Rail(pipeline,alignedFs,state, api, -1000)
                        break
                    elif L_ori > 800:
                        L_bias = 1000 - L_ori
                        Rail(pipeline,alignedFs,state, api, L_bias)
                    else:
                        Rail(pipeline,alignedFs,state, api, 200)
            else:
                L_ori = dType.GetPoseL(api)[0]
                if L_ori == 1000:
                    Rail(pipeline,alignedFs,state, api, -1000)
                    break
                elif L_ori > 800:
                    L_bias = 1000 - L_ori
                    Rail(pipeline,alignedFs,state, api, L_bias)
                else:
                    Rail(pipeline,alignedFs,state, api, 200)
            #boxs = np.load('temp.npy',allow_pickle=True)
            # Apply colormap on depth image (image must be converted to 8-bit per pixel first)
            #depth_colormap = cv2.applyColorMap(cv2.convertScaleAbs(depth_image, alpha=0.03), cv2.COLORMAP_JET)
            #cv2.line(color_image, (343, 0), (343, 480), (0, 0, 255), 1)
            # Stack both images horizontally
            #images = np.hstack((color_image, depth_colormap))
            # Show images
            #cv2.namedWindow('RealSense', cv2.WINDOW_AUTOSIZE)
            #cv2.imshow('RealSense', images)

            #key = cv2.waitKey(1)
            # Press esc or 'q' to close the image window
            #if key & 0xFF == ord('q') or key == 27:
            #    cv2.destroyAllWindows()
            #    break
    finally:
        # Stop streaming
        pipeline.stop()
if __name__ == "__main__":
    model = torch.hub.load('ultralytics/yolov5', 'custom', path='model/tomato.pt')
    model.conf = 0.5
    # Configure depth and color streams
    pipeline = rs.pipeline()

    cfg = rs.config()
    cfg.enable_stream(rs.stream.depth, 640, 480, rs.format.z16, 60)
    cfg.enable_stream(rs.stream.color, 640, 480, rs.format.bgr8, 60)

    # 設定需要對齊的方式（深度對彩色，彩色不變，深度變）
    align_to = rs.stream.color
    # 設定需要對齊的方式（彩色對深度，深度不變，彩色變）
    # align_to = rs.stream.depth

    alignedFs = rs.align(align_to)

    pipeline.start(cfg)

    api = dType.load()
    # Connect Dobot
    dType.DisconnectDobot(api)
    state = dType.ConnectDobot(api, "", 115200)[0]
    print("Connect status:", CON_STR[state])
    if (state == dType.DobotConnect.DobotConnect_NoError):
        dType.SetQueuedCmdClear(api)
        dType.SetHOMEParams(api, HOME['X'], HOME['Y'], HOME['Z'], HOME['R'], isQueued=1)
        wait_move = dType.SetHOMECmd(api, 0, isQueued=1)[0]
        dType.SetQueuedCmdStartExec(api)
        while wait_move > dType.GetQueuedCmdCurrentIndex(api)[0]:
            dType.dSleep(100)
        dType.SetQueuedCmdStopExec(api)
        run(pipeline,alignedFs,state,api,model)
        dType.DisconnectDobot(api)
        print('exit')
        cv2.destroyAllWindows()

