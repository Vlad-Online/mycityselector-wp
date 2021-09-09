#!/bin/bash
CMD="cd /var/www/html/wp-content/plugins/mcs && php vendor/bin/codecept run --steps ${@}"
docker-compose exec wordpress bash -c "${CMD}"
