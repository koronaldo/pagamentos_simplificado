Projeto Pagamento Simplificado

Laravel Framework 12.19.3
PHP 8.2.26
Banco Sqlite

Como instalar

Após baixar o projeto do git rode o comando

php artisan migrate:fresh --seed     

Para criar os usuários de teste ou registre novos usuário se necessário. Os usuarios tem a senha padrão 12345678

1@cliente.com.br
2@cliente.com.br
3@cliente.com.br
4@cliente.com.br

1@lojista.com.br
2@lojista.com.br
3@lojista.com.br
4@lojista.com.br

Lojistas

Após fazer o login o usuario terá acesso a uam tela para fazer transferência ou visualizar os 10 ulitmos movimentos realizados.

Pacotes  configurados

"php": "^8.2",
"laravel/framework": "^12.0",
"laravel/tinker": "^2.10.1",
"livewire/flux": "^2.1.1",
"livewire/volt": "^1.7.0"
