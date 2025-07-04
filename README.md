Projeto Pagamento Simplificado

Laravel Framework 12.19.3
PHP 8.2.26
Banco Sqlite

Como instalar

Após bixar o projeto do git rode o comando

php artisan migrate:fresh --seed     

Para criar os usuarios de teste

O sistema permite criar novos usuário se necessário.

Após fazer o login o usuario terá acesso a uam tela para fazer transferência ou visualizar os 10 ulitmos movimentos realizados.

Pacotes  configurados

"php": "^8.2",
"laravel/framework": "^12.0",
"laravel/tinker": "^2.10.1",
"livewire/flux": "^2.1.1",
"livewire/volt": "^1.7.0"

"require-dev": {
"fakerphp/faker": "^1.23",
"laravel-lang/lang": "^15.22",
"laravel-lang/publisher": "^16.6",
"laravel/pail": "^1.2.2",
"laravel/pint": "^1.18",
"laravel/sail": "^1.41",
"mockery/mockery": "^1.6",
"nunomaduro/collision": "^8.6",
"phpunit/phpunit": "^11.5.3"
