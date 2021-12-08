# Planning Tool

## About

Submission can be turned in for each month by web-interface. Evaluation can be downloaded as excel-file.

Two user roles: User and Manager with Access Control Interface for easy assignment.

## Technical

- Symfony5 PHP-Framework
- MariaDb

### Requirements

- php v.7.4.3
- composer v.2.1.11
- npm 8.2.0

## Deploy

### Database migration

- bin/console make:migration
- bib/console doctrine:migrations:migrate

### Install Requirements
- composer install
- npm install
- npm run build

### Setup environment
- Add .env.local file
```
APP_ENV=prod

DATABASE_URL="mysql://pmtuser:passw0rd@127.0.0.1:3306/pmt-db"

MAILER_DSN=sendgrid+smtp://SG.xxx_here_sendgrid_key_xxx@default
```