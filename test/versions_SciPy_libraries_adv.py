from sys import version
print('python: %s' % version)

# check library version numbers
# scipy
import scipy
print('scipy: %s'  %scipy.__version__)
# numpy
import numpy
print('numpy: %s' % numpy.__version__)
# matplotlib (conda install matplotlib)
import matplotlib
print('matplotlib: %s' % matplotlib.__version__)
# pandas(conda install pandas)
import pandas 
print('pandas: %s' % pandas.__version__)
# statsmodels (conda install statsmodels(

import statsmodels
print('statsmodels: %s' % statsmodels.__version__)
# pillow (conda install pillow)
# Maybe also needs-->pip install image (need to confirm this more???)
#[Because some applications need it-->from PIL import Image)
import PIL
print('PIL: %s' % PIL.__version__)
# scikit-learn (conda install scikit-learn)
import sklearn 
print('sklearn: %s' % sklearn.__version__)
# pyyaml
import yaml
print('yaml: %s' % yaml.__version__)
# scikit-image (pip install scikit-image)
import skimage
print('skimage: %s' % skimage.__version__)
# ipython(conda install -c anaconda ipython)
import IPython
print('IPython: %s' % IPython.__version__)
# opencv-python(pip install opencv-python)
import cv2
print('cv2: %s' % cv2.__version__)
# mtcnn(pip install mtcnn)
import mtcnn
print('mtcnn: %s' % mtcnn.__version__)
# keras-vggface(pip install git+https://github.com/rcmalli/keras-vggface.git)
import keras_vggface
print('keras_vggface: %s' % keras_vggface.__version__)