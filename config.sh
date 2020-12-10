#!/bin/bash

cd sales-api

if [ ! -f .env ]; then

  cp .env.example .env

  appKey=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)

  sed -i "s+APP_KEY=+APP_KEY=$appKey+g" .env
  sed -i "s+APP_NAME=+APP_NAME=$APP_NAME+g" .env
  sed -i "s+APP_URL=+APP_URL=$APP_URL:$APP_PORT+g" .env
  sed -i "s+APP_TIMEZONE=+APP_TIMEZONE=$APP_TIMEZONE+g" .env

else

  echo '.env file exists, please remove and build your app again'

fi


