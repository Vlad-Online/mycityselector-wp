#!/bin/bash
[[ ! -d build ]] && mkdir build
rm -rf build/*
[[ ! -d build/mcs ]] && mkdir build/mcs
cd admin && yarn && yarn build
cd ../widget && yarn && yarn build
cd ..
rsync -av --exclude '/build/' --exclude '/tests/' --exclude 'node_modules/' --exclude '.git/' --exclude 'GeoLite2-City-Locations-en.csv'  ./ build/mcs
cd build/mcs && composer i --no-dev
zip -r ../mcs.zip ./
cd ../..
rm -rf build/mcs
