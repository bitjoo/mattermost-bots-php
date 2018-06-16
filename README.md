# Mattermost Chatbots

These Chatbots can be hosted on a server with PHP 7+.
The Application is written with the Slim Framework 3.

Following Chatbots are available:
- GiphyBot

## Settings

After you uploaded the files to your server, you have to modify `src/settings.php`

Replace the placeholder `PLACE_TOKEN_HERE` with your integration token provided by mattermost.

## Run on Ubuntu 16.04

### Install PHP
```
sudo apt-get install php php-xml
```

### Install dependencies
```
php composer.phar install
```

### Start PHP server
With a standard timeout of 300 seconds
```
php composer.phar start
```

With no timeout
```
php composer.phar run-script start --timeout=0
```
