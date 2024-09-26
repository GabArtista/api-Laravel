# Projeto de API com Laravel e Sanctum

## Descrição
Este projeto é uma API desenvolvida em Laravel que utiliza o Laravel Sanctum para autenticação de usuários. Ele permite o cadastro, login e manipulação de usuários de forma segura e eficiente.

## Pré-requisitos
Antes de começar, você precisa ter os seguintes itens instalados:

- PHP >= 7.3
- Composer
- MySQL ou MariaDB
- Laravel

## Instalação
Siga os passos abaixo para configurar o projeto:

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/seu-usuario/seu-repositorio.git

**Passos:**

   ```bash

cd seu-repositorio

```bash

composer install


```bash

cp .env.example .env


```bash

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha


```bash

php artisan key:generate


```bash

php artisan migrate


```bash

composer require laravel/sanctum


```bash

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"


```bash

php artisan serve


