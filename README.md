# Loopa Digital - Matheus Silva da Cruz

## Setup do projeto

-   Linguagem: PHP 7.4
-   Framework: Lumen versão 8.0.1 / Laravel Components ^8.0
-   Nginx

## Dependências

-   Docker

## Collections

-   Postman
-   Insomnia

### Disponíveis na pasta `collections` na raiz do projeto

## Instruções para execução

### Execute o arquivo `Makefile` na raiz do projeto

`make init`

Este comando irá executar os seguintes passos:

1. Criar o arquivo .env com base no .env-example
2. Subir os containers Docker em sua máquina local
3. Instalação das dependências
4. Execução dos testes

Você poderá acompanhar o progresso no terminal.

Após esses passos o ambiente poderá ser acessado através da url: **http://localhost:8000**
