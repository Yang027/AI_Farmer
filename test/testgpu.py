import torch
import tensorflow as tf
print(tf.test.is_gpu_available())
print(torch.cuda.is_available())