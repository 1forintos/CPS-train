import pika, os, urlparse, logging
import base64
logging.basicConfig()

# load and encode image
with open("res/Color-red.jpg", "rb") as image_file:
    image_base64 = base64.b64encode(image_file.read())

# Parse CLODUAMQP_URL (fallback to localhost)
url = os.environ.get('CLOUDAMQP_URL', 'amqp://guest:guest@192.168.0.2/cps')
params = pika.URLParameters(url)
params.socket_timeout = 5
connection = pika.BlockingConnection(params) # Connect to CloudAMQP
channel = connection.channel() # start a channel

# send a message
channel.basic_publish(exchange='', routing_key='TEST_QUEUE', body=image_base64)
print " [x] Image sent"


connection.close()