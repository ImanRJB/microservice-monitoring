## Milyoona Microservice Monitoring

#### How to install

```bash
composer require milyoona/microservice-monitor
```

###### Publish configuration and database files:

```bash
php artisan vendor:publish --tag=microservice-monitor
```

###### Add monitoring tool in <code>Laravel Nova</code>:

```php
// NovaServiceProvider.php - tools method
new \Milyoona\MicroserviceMonitor\MicroserviceMonitor;
```

###### Use these directives for <code>supervisor configs</code> in .env file

```dotenv
SUPERVISOR_URL=
SUPERVISOR_USERNAME=
SUPERVISOR_PASSWORD=
````