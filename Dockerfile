FROM alfg/nginx-rtmp:latest

RUN apk add --no-cache bash jq php php-cli php-fpm php-json

WORKDIR /app

COPY nginx.template.conf /app/
COPY entrypoint.sh /app/
COPY servers.json /app/
COPY panel /var/www/html/

RUN chmod +x /app/entrypoint.sh

CMD ["/app/entrypoint.sh"]
