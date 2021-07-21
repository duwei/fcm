FROM node:alpine

WORKDIR /app

RUN apk update && \
    npm install -g npm && \
    npm install -g @vue/cli