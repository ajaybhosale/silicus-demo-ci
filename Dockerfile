FROM silicus.azurecr.io/silicus-php-demo-base:v1

MAINTAINER Ajay Bhosale<ajay.bhosale@silicus.com>

# Pull Source Code
COPY workspace /var/www/html/workspace

# Laravel required commands
WORKDIR /var/www/html/workspace