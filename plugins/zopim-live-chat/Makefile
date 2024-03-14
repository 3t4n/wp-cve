.PHONY: zdi build start visit stop shell

IMGNAME = zendesk_chat_wordpress
PORT = 8001

default: stop zdi build start visit

# start the required zdi services and will create database if it does not exist
zdi:
	if zdi consul restart ; then true ; fi
	if zdi dnsmasq restart ; then true ; fi
	if zdi mysql restart ; then true ; fi
	docker exec -i mysql mysql -uadmin -p123456 -h192.168.42.45  \
	<<< "CREATE DATABASE IF NOT EXISTS $(IMGNAME);"

# build the docker image
build:
	docker build -t $(IMGNAME) -f dev/Dockerfile .

# start a docker container using the docker image
start:
	docker run --name $(IMGNAME) -p $(PORT):80 -d $(IMGNAME)
	sleep 1

# visit the Wordpress site
visit:
	open http://192.168.42.45:$(PORT)/admin

# stop the docker container if it's present
stop:
	if docker rm -f $(IMGNAME) ; then true ; fi

# login to docker container
shell:
	docker exec -it $(IMGNAME) /bin/bash
