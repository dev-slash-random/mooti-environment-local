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

# Start Vagrant
vagrant up
#vagrant provision

#vagrant ssh -c "cd /apps/mooti-service && bundle install"
#vagrant ssh -c "cd /apps/mooti-api && ./bin/vbox-init"
#vagrant ssh -c "cd /apps/mooti-marketing && ./bin/vbox-init"

#vagrant ssh -c "sudo /etc/init.d/apache2 restart"