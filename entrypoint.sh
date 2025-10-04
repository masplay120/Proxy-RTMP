#!/bin/bash
set -e

generate_nginx_conf() {
  apps=""
  auths=""

  servers=$(jq -c '.[]' /app/servers.json)
  for row in $servers; do
    id=$(echo $row | jq -r '.id')
    clave=$(echo $row | jq -r '.clave')
    origen=$(echo $row | jq -r '.origen')
    activo=$(echo $row | jq -r '.activo')

    if [ "$activo" = "true" ]; then
      apps="$apps
        application $id {
            live on;
            on_publish http://127.0.0.1:8081/auth?id=$id;
            allow publish all;
            allow play all;
            push $origen;
        }"
      auths="$auths
        location /auth {
            if (\$arg_id = \"$id\") {
                if (\$arg_name != \"$clave\") { return 403; }
                return 200;
            }
        }"
    fi
  done

  sed "s|{{APPLICATIONS}}|$apps|; s|{{AUTH}}|$auths|" /app/nginx.template.conf > /etc/nginx/nginx.conf
}

generate_nginx_conf

# Inicia nginx
nginx -g "daemon off;"
