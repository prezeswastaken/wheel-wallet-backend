# How to run this?
- copy **.env.example** file to **.env** file and adjust it to your likink
- now you need to install composer dependencies to this project with a **temporary docker container**. To do this, run
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```
- run `./vendor/bin/sail up -d`
- now you can check if the api is up by accessing localhost/api/hello route. If you get json response, congratulations! You have now successully run **Wheel Wallet** backend server!
