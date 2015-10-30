#!/usr/bin/env bash

apt-get update
apt-get install -y apache2 php5 php5-curl openssl

if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant/public_html /var/www
fi

if ! [ -L /var/secure ]; then
  rm -rf /var/secure
  ln -fs /vagrant/secure /var/secure
fi

if ! [ -L /etc/apache2/sites-available/default.conf ]; then
  rm -rf /etc/apache2/sites-available/default.conf
  ln -fs /vagrant/sites-available/default.conf /etc/apache2/sites-available/default.conf
fi

if ! [ -L /etc/php.ini ]; then
  rm -rf /etc/php.ini
  ln -fs /vagrant/php/php.ini /etc/php.ini
else
  ln -fs /vagrant/php/php.ini /etc/php.ini
fi