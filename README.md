<!-- ABOUT THE PROJECT -->

## About The Project

Notification module made as a recruitment task

### Built With

- [PHP](https://www.php.net/)
- [Symfony](https://symfony.com/)

<!-- GETTING STARTED -->

## Getting Started

### Installation

Follow these simple steps

#### Clone API repository

```bash
dev:$ git clone git@github.com:RyuuKodex/transfer-go-task.git
```

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up` (the logs will be displayed in the current shell)
4. Open `https://localhost` in your favorite web browser
   and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. In .env file add your Twilio and Amazon SES credentials

## Endpoint

#### Create and send notification

```http
  GET /api/notification
```

| In Body    | Type     | Example                              |
|:-----------|:---------|:-------------------------------------|
| `sender`   | `string` | 61407079-0246-47ae-8077-29039e5d798e |
| `receiver` | `string` | 494572c8-d727-4753-b39f-da2fe935cd68 |
| `title`    | `string` | title                                |
| `message`  | `string` | message                              |

## Commands

#### Start the project

```bash
dev:$ docker-compose up -d
```

#### Connect to PHP docker container

```bash
dev:$ docker-compose exec -it app bash
```

#### Stop project

```bash
dev:$ docker-compose down --remove-orphans
```

#### Run tests in container

```bash
dev:$ APP_ENV=test php bin/phpunit
```

#### Run csfixer in container

```bash
dev:$ vendor/bin/php-cs-fixer fix src
```
