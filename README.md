# Projeto de API com Laravel e Sanctum

## Descrição

Este projeto é uma API desenvolvida em Laravel que utiliza o Laravel Sanctum para autenticação de usuários. Ele permite o cadastro, login e manipulação de usuários de forma segura e eficiente.

## Pré-requisitos

Antes de começar, você precisa ter os seguintes itens instalados:

-   PHP >= 7.3
-   Composer
-   MySQL ou MariaDB
-   Laravel

## Instalação

Siga os passos abaixo para configurar o projeto:

1. **Clone o repositório:**

    ```bash
    git clone https://github.com/seu-usuario/seu-repositorio.git
    ```

**Passos:**

```bash

cd seu-repositorio



composer install



cp .env.example .env




DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha



php artisan key:generate




php artisan migrate




composer require laravel/sanctum


## Para criar um autenticador.

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"




php artisan serve



## Para criar a classe "ProdutoCadastrado" Para enviar o E-mail use:
php artisan make:mail ProdutoCadastrado

## Para criar a classe "ProdutoEditado" Para enviar o E-mail use:
php artisan make:mail ProdutoEditado

```
