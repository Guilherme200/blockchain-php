FROM node:9.11.1-alpine

RUN npm install -g ganache-cli

EXPOSE 8545

CMD ganache-cli -g 0 -l 6000000 --hostname=0.0.0.0