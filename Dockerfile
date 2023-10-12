FROM ubuntu:22.04

WORKDIR /var/www/html
ENV TZ=America/Sao_Paulo

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY . .

RUN apt-get update \
    && apt install tzdata \
    && apt install software-properties-common -y \
    && add-apt-repository ppa:ondrej/php \
    && apt update -y \
    && apt install -y \
    apache2 \
    php8.2 \
    libapache2-mod-php8.1 \
    php8.2-mysql \
    php8.2-mbstring \
    php8.2-curl \
    php8.2-xml \
    php8.2-bcmath \
    php8.2-gd \
    php8.2-ldap \
    net-tools \
    iputils-ping \
    php8.2-zip \
    curl \
    nano

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install

COPY ./config_apache/sites/dev.local.conf /etc/apache2/sites-available
COPY ./config_apache/*.conf /etc/apache2
COPY ./config_apache/php.ini /etc/php/8.2/apache2

RUN a2dissite 000-default.conf \
    default-ssl.conf \
    && a2ensite dev.local.conf \
    && a2enmod rewrite \
    && a2enmod ssl

EXPOSE 80 443

CMD ["apachectl", "-D", "FOREGROUND"]
