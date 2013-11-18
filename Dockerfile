from   base
env    DEBIAN_FRONTEND noninteractive

run    dpkg-divert --local --rename --add /sbin/initctl
run    ln -s /bin/true /sbin/initctl

run    apt-get install -y -q software-properties-common
run    add-apt-repository -y "deb http://archive.ubuntu.com/ubuntu $(lsb_release -sc) universe"
run    add-apt-repository -y ppa:nginx/stable
run    add-apt-repository -y ppa:ondrej/php5
run    apt-get --yes update
run    apt-get --yes upgrade --force-yes

# Preconfigure passwords
run   echo "mysql-server mysql-server/root_password password docker" | debconf-set-selections
run   echo "mysql-server mysql-server/root_password_again password docker" | debconf-set-selections

# Basic Requirements
RUN apt-get -y --force-yes install mysql-server mysql-client nginx php5-fpm php5-mysql pwgen python-setuptools curl git wget unzip supervisor

# Wordpress Requirements
RUN apt-get -y --force-yes install php5-curl php5-gd php5-intl php-pear php5-imagick php5-imap php5-mcrypt php5-memcache php5-ming php5-ps php5-pspell php5-recode php5-snmp php5-sqlite php5-tidy php5-xmlrpc php5-xsl 
run apt-get install -y --force-yes php5-xdebug php5-xcache


# mysql config
RUN sed -i -e"s/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" /etc/mysql/my.cnf

# nginx config
RUN sed -i -e"s/keepalive_timeout\s*65/keepalive_timeout 2/" /etc/nginx/nginx.conf
# since 'upload_max_filesize = 2M' in /etc/php5/fpm/php.ini
RUN sed -i -e"s/keepalive_timeout 2/keepalive_timeout 2;\n\tclient_max_body_size 3m/" /etc/nginx/nginx.conf
RUN echo "daemon off;" >> /etc/nginx/nginx.conf

# php-fpm config
RUN sed -i -e "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" /etc/php5/fpm/php.ini
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php5/fpm/php-fpm.conf
RUN find /etc/php5/cli/conf.d/ -name "*.ini" -exec sed -i -re 's/^(\s*)#(.*)/\1;\2/g' {} \;

RUN echo "[xdebug]" >> /etc/php5/fpm/php.ini
RUN echo "zend_extension=/usr/lib/php5/20121212/xdebug.so" >> /etc/php5/fpm/php.ini
RUN echo "xdebug.remote_enable=1" >> /etc/php5/fpm/php.ini
RUN echo "xdebug.remote_connect_back=1" >> /etc/php5/fpm/php.ini
RUN echo "xdebug.remote_port=9000" >> /etc/php5/fpm/php.ini
RUN echo "xdebug.show_local_vars=0" >> /etc/php5/fpm/php.ini
RUN echo "xdebug.var_display_max_data=10000" >> /etc/php5/fpm/php.ini
RUN echo "xdebug.var_display_max_depth=20" >> /etc/php5/fpm/php.ini
RUN echo "xdebug.show_exception_trace=0" >> /etc/php5/fpm/php.ini

ADD ./nginx/default.conf /etc/nginx/sites-available/default

add    ./supervisor/supervisord.conf /etc/supervisor/supervisord.conf
add    ./supervisor/conf.d/nginx.conf /etc/supervisor/conf.d/nginx.conf
add    ./supervisor/conf.d/mysqld.conf /etc/supervisor/conf.d/mysqld.conf
add    ./supervisor/conf.d/php5-fpm.conf /etc/supervisor/conf.d/php5-fpm.conf


expose 80
volume ["/srv/wordpress"]
ENTRYPOINT ["/usr/bin/supervisord"]