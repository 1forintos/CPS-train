import pika, os, urlparse, logging
import base64
import time
from RPi import GPIO
import argparse

logging.basicConfig()

parser = argparse.ArgumentParser()
parser.add_argument("--trainColor", help="The base color of the image that will be sent to the cloud. [red|green]")
args = parser.parse_args()

PIN_LED_RED = 7
PIN_LED_GREEN = 11
PIN_OPTO_1 = 13
PIN_OPTO_2 = 15

DISTANCE = 0.00003

def getTrainImage(trainColor):
	# load and encode image
	with open("res/" + trainColor + ".jpg", "rb") as image_file:
	    return base64.b64encode(image_file.read())

def processResult(ch, method, properties, body):
	if(body == "RED"):
		GPIO.output(PIN_LED_RED, 1)
		GPIO.output(PIN_LED_GREEN, 0)
	if(body == "GREEN"):
		GPIO.output(PIN_LED_RED, 0)
		GPIO.output(PIN_LED_GREEN, 1)
	ch.stop_consuming()

def log_msg(msg):
	print " [CPS-train] " + msg

def check_opto_channels():
	while(True):
		log_msg("Channels: " + str(GPIO.input(PIN_OPTO_1)) + " " + str(GPIO.input(PIN_OPTO_2)))
		time.sleep(0.5)

if(args.trainColor == None):
	log_msg("Starting cps in speed detection mode...")
else:
	if(args.trainColor != "red" and args.trainColor != "green"):
		log_msg("Train color has to be red or green.")
		exit()
	log_msg("Starting cps in color detection mode...")

GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)

GPIO.setup(PIN_LED_RED, GPIO.OUT)
GPIO.setup(PIN_LED_GREEN, GPIO.OUT)
GPIO.setup(PIN_OPTO_1, GPIO.IN)
GPIO.setup(PIN_OPTO_2, GPIO.IN)

GPIO.output(PIN_LED_RED, 0)
GPIO.output(PIN_LED_GREEN, 0)

# setup connection
url = os.environ.get('CLOUDAMQP_URL', 'amqp://qvofpctp:-I8fYvnDLSPDp5Y4DFCpuBmsHEzVxxEY@white-swan.rmq.cloudamqp.com/qvofpctp')
params = pika.URLParameters(url)
params.socket_timeout = 5
connection = pika.BlockingConnection(params) # Connect to CloudAMQP
channel = connection.channel() # start a channel

# check_opto_channels()

while(True):
	send_blue = True
	if(GPIO.input(PIN_OPTO_1) == 0):
		time_start = time.time()
		go = True
		while(go):
			if(GPIO.input(PIN_OPTO_2) == 0):
				# hour
				time_diff = (time.time() - time_start) / 3600
				speed_km_p_hr = (DISTANCE / time_diff)
				log_msg("Speed: " + str(speed_km_p_hr) + " km/h")
				if(args.trainColor != None):
					image_base64 = getTrainImage(args.trainColor)
					channel.basic_publish(exchange='', routing_key='TRAIN_IMAGES', body=image_base64)
				channel.basic_publish(exchange='', routing_key='TRAIN_SPEED', body=str(speed_km_p_hr))
				channel.basic_consume(processResult, queue='LED', no_ack=True)
				channel.start_consuming()
				go = False

# Close connection to CloudAMQP
connection.close()