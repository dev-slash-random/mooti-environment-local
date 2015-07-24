#!/bin/bash

mkdir -p ./apps
mkdir -p ./apps/services

# Clone the services
SERVICES=(
    'account'
)

for i in "${SERVICES[@]}"
    do
    if [ ! -d apps/services/$i.service.dev.mooti.com ]; then
        git clone git@github.com:mooti/mooti-service-$i.git ./apps/services/$i.service.dev.mooti.com
    else
    	cd ./apps/services/$i.service.dev.mooti.com
    	git pull
    	cd ../../..
    fi
done

# Clone support repos
APPS=(
    'xizlr-core'
)

for i in "${APPS[@]}"
    do
    if [ ! -d apps/$i ]; then
        git clone git@github.com:mooti/$i.git ./apps/$i
    else
    	cd ./apps/$i
    	git pull
    	cd ../..
    fi
done

# Start Vagrant
(vagrant status | grep running) || 
	vagrant up --provision

(vagrant status | grep poweroff) || 
	vagrant provision

#vagrant provision

for i in "${SERVICES[@]}"
    do
    vagrant ssh -c "cd /vagrant/apps/services/$i.service.dev.mooti.com && composer install"
done

for i in "${APPS[@]}"
    do
    vagrant ssh -c "cd /vagrant/apps/$i && composer install"
done
