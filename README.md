# TransferGo recruitment task

## About The Project

Notification module made as a recruitment task

### Built With

- [PHP](https://www.php.net/)
- [Symfony](https://symfony.com/)
- [Docker](https://www.docker.com/)
- [Compose](https://docs.docker.com/compose/)

## Getting Started

### Installation

Follow these simple steps

#### Clone API repository

```bash
dev:$ git clone git@github.com:RyuuKodex/transfer-go-task.git
```

1. Run `docker-compose build --pull --no-cache` to build fresh images
2. Run `docker-compose up` (the logs will be displayed in the current shell)
3. In .env or .env.local file add your Twilio and Amazon SES credentials

## Endpoint

#### Create and send notification

```http
  POST /api/notification
```

```json
{
    "sender": "61407079-0246-47ae-8077-29039e5d798e",
    "receiver": "494572c8-d727-4753-b39f-da2fe935cd68",
    "title": "title",
    "message": "message"
}

```

## Commands

#### Start the project

```bash
dev:$ docker-compose up -d
```

#### Connect to app container

```bash
dev:$ docker-compose exec -it app bash
```

#### Stop project

```bash
dev:$ docker-compose down --remove-orphans
```

#### Run tests in the container

```bash
dev:$ APP_ENV=test php bin/phpunit
```

#### Run code lint in the container

```bash
dev:$ vendor/bin/php-cs-fixer fix
```
