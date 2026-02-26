
<img width="1609" height="791" alt="mechinearm" src="https://github.com/user-attachments/assets/0811c47b-6fa1-4c0b-a6a1-f3c48689e6c5" />

# 智慧機農作（番茄採摘）機械手臂控制系統架構說明

這個專案是一個**基於電腦視覺與 AI 的智慧農作（番茄採摘）機械手臂控制系統**。它結合了深度攝影機、物件偵測 AI 模型，以及機械手臂的硬體控制，並具有 MQTT 遠端遙控的擴充性。

以下為專案架構與各模組核心功能的詳細說明：

## 1. 硬體與技術堆疊 (Hardware & Tech Stack)
* **機械手臂 (Robotic Arm):** 使用 **Dobot** 廠牌的機械手臂（支援滑軌移動）。透過呼叫原廠提供的 C/C++ 動態連結庫（`libDobotDll.so` 等）與 Python wrapper (`DobotDllType.py`) 進行通訊與動作控制。
* **視覺感測器 (Vision Sensor):** 使用 **Intel RealSense** 深度攝影機（透過 `pyrealsense2` 函式庫）。負責同時擷取彩色影像 (RGB) 與深度影像 (Depth)，用以判斷目標物的三維空間位置。
* **AI 辨識模型 (AI Model):** 使用 **PyTorch 與 YOLOv5** 架構，載入自定義的權重檔 `model/tomato.pt`，專門用來辨識影像中的「成熟番茄」(`mature_tomato`)。
* **遠端通訊 (Communication):** 使用 **MQTT** 協定實現輕量級的遠端指令接收。

## 2. 核心檔案與模組架構 (Core Modules)

### 🎥 核心主程式：`main_v3_display.py`
這是整個專案的靈魂，負責串接所有的軟硬體邏輯。其運作流程如下：
1. **初始化與連線:** 
   * 載入 YOLOv5 番茄偵測模型。
   * 啟動 RealSense 攝影機的資料流，並設定深度與彩色影像對齊。
   * 初始化並連線至 Dobot 機械手臂，完成歸零 (`Home`) 動作。
2. **視覺辨識迴圈 (`run` 函式):**
   * 不斷從攝影機讀取畫面，將彩色影像送入 YOLOv5 進行推論，取得 `mature_tomato` 的 Bounding Box（邊界框）。
3. **距離計算與濾除 (`get_mid_pos`, `get_center_distance`):**
   * 取出被偵測為成熟番茄的物件，利用 RealSense 的深度資料計算該番茄與攝影機的實際物理距離。
   * 根據手臂長度限制，過濾掉「太遠」或「無法抓取」的番茄。
4. **控制手臂與滑軌 (`pick`, `Rail` 函式):**
   * 依據最近的一顆番茄，換算機械手臂所需要的位移量（X、Z 軸偏移，或是滑軌的 L 軸偏移）。
   * 指令手臂移動到目標上方、下降並啟用夾爪 (`EndEffectorGripper`) 進行採摘，隨後放置到指定區域並復位。

### 📡 遠端遙控接收器：`mqtt_Subscribe.py`
負責接收遠端的啟動或其他系統命令：
* 實作了一個 MQTT 的訂閱者 (Subscriber)，連線至伺服器 `192.192.156.30`，訂閱主題為 `robot/arm`。
* 當接收到訊息後，會直接透過 `os.system(cmd)` 執行系統指令。這通常用於遠端啟動主程式、更新程式碼或進行系統重開機。

### ⚙️ 底層硬體驅動層
* **`DobotDllType.py` / `DobotDllType_mock.py`:** 機械手臂控制 API 的 Python 封裝，定義了與原廠 DLL 溝通的方法、常數（例如各關節模式 PTP、連線狀態等）。`_mock` 結尾的檔案可能是用於開發階段無硬體時的模擬。
* **`dobot_rail.py`:** 單純的測試或初始化腳本，用來確保機械手臂與底座滑軌能夠正常連線並切換設備模式。
* **各類 `.so` 檔案:** 包含 `libDobotDll.so` 與 `pyrealsense2.*.so`，皆為在 Linux/ARM 架構下（如 Raspberry Pi, Jetson Nano）運行依賴的低階編譯檔。

## 架構總結
這是一個標準的 **「端到端 (Edge AI)」自動化機器人專案**：
`攝影機捕捉畫面 -> YOLO AI 找出目標位置 -> 解析深度換算 3D 座標 -> 透過 API 驅動 Dobot 進行空間移位與抓取`，最後透過 MQTT 留有遠端操作的接口。從 `.so` 檔案名來看，該系統目前主要被部署在一台 64位元 ARM 架構 (aarch64) 的邊緣計算設備上。
