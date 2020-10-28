# Loopa Digital - Matheus Silva da Cruz

## Setup do projeto

-   Linguagem: PHP 7.4
-   Framework: Lumen versão 8.0.1 / Laravel Components ^8.0
-   Nginx

## Dependências

-   Docker

## Instruções para execução

### Execute o arquivo `Makefile` na raiz de cada projeto:

`make init`

Este comando irá executar os seguintes passos:

1. Criar o arquivo .env com base no .env-example
2. Subir os containers Docker em sua máquina local
3. Instalação das dependências

Você poderá acompanhar o progresso no terminal.

Após esses passos o ambiente poderá ser acessado através da url: **http://localhost:8000**
