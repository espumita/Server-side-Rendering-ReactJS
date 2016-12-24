FROM php:7.1.0-apache

MAINTAINER davidfalconnaranjo@gmail.com

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '61069fe8c6436a4468d0371454cf38a812e451a14ab1691543f25a9627b97ff96d8753d92a00654c21e2212a5ae1ff36') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --version=1.3.0 --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"


RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
        git subversion make g++ python2.7 curl chrpath wget bzip2 && \
    ln -s /usr/bin/python2.7 /usr/bin/python && \
    \
    git clone https://chromium.googlesource.com/chromium/tools/depot_tools.git /tmp/depot_tools && \
    export PATH="$PATH:/tmp/depot_tools" && \
    \
    cd /usr/local/src && fetch v8 && cd v8 && \
    git checkout 5.4.500.40 && gclient sync && \
    export GYPFLAGS="-Dv8_use_external_startup_data=0" && \
    export GYPFLAGS="${GYPFLAGS} -Dlinux_use_bundled_gold=0" && \
    make native library=shared snapshot=on -j4 && \
    \
    mkdir -p /usr/local/lib && \
    cp /usr/local/src/v8/out/native/lib.target/lib*.so /usr/local/lib && \
    echo "create /usr/local/lib/libv8_libplatform.a\naddlib out/native/obj.target/src/libv8_libplatform.a\nsave\nend" | ar -M && \
    cp -R /usr/local/src/v8/include /usr/local && \
    chrpath -r '$ORIGIN' /usr/local/lib/libv8.so && \
    \
    git clone https://github.com/phpv8/v8js.git /usr/local/src/v8js && \
    cd /usr/local/src/v8js && phpize && ./configure --with-v8js=/usr/local && \
    export NO_INTERACTION=1 && make all -j4 && make test install && \
    \
    cd /tmp && \
    rm -rf /tmp/depot_tools /usr/local/src/v8 /usr/local/src/v8js && \
    apt-get remove -y subversion make g++ python2.7 curl chrpath wget bzip2 && \
    apt-get autoremove -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y --no-install-recommends curl

COPY src/ /var/www/html/
COPY composer.json /var/www/html/
COPY config/php.ini /usr/local/etc/php/

WORKDIR /var/www/html/

RUN composer install --no-interaction \
    && composer update