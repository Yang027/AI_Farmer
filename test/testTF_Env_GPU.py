# -*- coding: utf-8 -*-
"""
Created on Sun Aug 22 00:42:40 2021

@Project: Test tensorflow with GPU
@author: JLL

"""
##### Environment - log message
# system log info.: must be first statement of program
import os
#os.environ['TF_CPP_MIN_LOG_LEVEL'] = '0' # 所有訊息, this is default in spyder 
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '1' # 警告(WARNING)、錯誤(ERROR)
#os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2' # 錯誤(ERROR)
#os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3' # 致命錯誤(FATAL)

##### Test tensforflow with GPU
from tensorflow.python.client import device_lib
print("\n=====\n", device_lib.list_local_devices())

##### Package version: pip list or pip show packageName
#---- Packages: general purpose
#-- scipy
import scipy
print('\n===== scipy: %s' % scipy.__version__)

#-- numpy
import numpy
print('===== numpy: %s' % numpy.__version__)

#-- statsmodels
import statsmodels
print('===== statsmodels: %s' % statsmodels.__version__)

#---- Packages for data processing
#-- pandas
import pandas
print('===== pandas: %s' % pandas.__version__)

#---- Packages for visualization
#-- matplotlib
import matplotlib
print('===== matplotlib: %s' % matplotlib.__version__)

#-- seaborn
import seaborn
print('===== seaborn: %s' % seaborn.__version__)

#-- dot visualization
import graphviz
print('===== graphviz: %s' % graphviz.__version__)

#-- computer vision !pip install opencv-python
import cv2 # pip install opencv-python
print('===== opencv: %s' % cv2.__version__)

#---- Packages for machine learning
#-- scikit-learn
import sklearn
print('===== sklearn: %s' % sklearn.__version__)

#-- tensorflow
import tensorflow
print('===== tensorflow-gpu: %s' % tensorflow.__version__) # if with GPU 

#-- h5 file 
import h5py
print('===== h5py: %s' % h5py.__version__)

#-- tensorboard
import tensorboard
print('===== tensorboard: %s' % tensorboard.__version__)

