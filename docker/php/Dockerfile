FROM php:8.2-fpm-buster
ARG USERNAME
ARG UID
ARG GIT_EMAIL
ARG GIT_NAME
RUN apt-get update && apt-get install -y sudo \
    procps \
    git \
    wget \
    nano \
    coreutils \
    p7zip-full \
    zip \
    unzip\
    libpq-dev \
    postgresql
#symfony cli install and docker user with privileges

RUN docker-php-ext-install pdo pgsql pdo_pgsql mysqli opcache
RUN useradd -m -u ${UID} -s /bin/bash ${USERNAME} \
    && usermod -aG sudo ${USERNAME} && echo "${USERNAME} ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers.d/${USERNAME} \
    && chmod 0440 /etc/sudoers.d/${USERNAME} \
    && wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
#Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer --2.2 && \
    php -r "unlink('composer-setup.php');" \
    && sudo chown -R "${USERNAME}:${USERNAME}" /var/www/html
#Change to our username
USER $USERNAME:$USERNAME
WORKDIR /var/www/html/application
RUN git config --global user.email "${GIT_EMAIL}" && git config --global user.name "${GIT_NAME}"








