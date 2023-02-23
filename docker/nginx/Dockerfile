FROM nginx:stable
COPY default.conf /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf
RUN apt-get update && apt-get install -y sudo \
    nano
RUN sed -i "s/{project_root}/application/g" /etc/nginx/conf.d/default.conf
RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf