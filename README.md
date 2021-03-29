# Instalação
O projeto se encontra na pasta `sales-api`. 
Antes de poder executar o projeto, deve-se instalar as dependências do projeto através do composer. 
Assumindo o uso de um ambiente unix, pode-se usar os seguintes comandos:
```shell
cd sales-api/
composer install
```

Com as dependências instaladas, e dentro da pasta `sales-api`, o servidor da api pode ser iniciado, e os testes executados.
Para executar os testes automatizados da aplicação, é só executar o seguinte comando dentro da pasta 'sales-api':
```shell
./vendor/phpunit
```
Ou
```shell
php ./vendor/phpunit
```
E para inicia o servidor da api, é só executar o seguinte comando dentro da pasta da api:
```shell
php -S localhost:8080 -t public
```
Com isso a aplicação já está pronta para receber requisições na porta 8080, sendo a porta qualquer uma que desejar. 
A aplicação disponibiliza apenas uma rota que deve receber um post com o arquivo a ser interpretado, e retorna um json com a informação das vendas.
A rota é `/sales`:
```
http://localhost:8080/sales
```

O name do parâmetro que a api espera receber o arquivo é 'file'.
Para os testes, pode-se utilizar um programa como Postaman ou Insomnia para fazer o envio dos arquivos de teste.
Com o Insomnia, por exemplo, é só enviar o arquivo escolhido como um parâmetro de Multipart Form com o nome 'file'.
Foi utilizado o arquivo `sales.txt` na raiz do repositório para testes, e variações de alguns dos valores contidos no arquivo.
Ao fazer o post do arquivo `sales.txt` para a rota citada anteriormente, será retornado a seguinte resposta:
```JSON
{
  "sales": [
    {
      "id": "123",
      "date": "2020-10-12",
      "amount": 1113.27,
      "customer": {
        "name": "Comprador 1",
        "address": {
          "street": "Rua Deodate Pereira Rezende",
          "neighborhood": "Jaguaribe",
          "city": "Osasco",
          "state": "SP",
          "postal_code": "06050-190"
        }
      },
      "installments": [
        {
          "installment": 1,
          "amount": 371.09,
          "date": "2020-11-12"
        },
        {
          "installment": 2,
          "amount": 371.09,
          "date": "2020-12-14"
        },
        {
          "installment": 3,
          "amount": 371.09,
          "date": "2021-01-12"
        }
      ]
    },
    {
      "id": "321",
      "date": "2020-10-13",
      "amount": 1563.75,
      "customer": {
        "name": "Comprador 2",
        "address": {
          "street": "Estrada do Copiúva",
          "neighborhood": "Vila da Oportunidade",
          "city": "Carapicuíba",
          "state": "SP",
          "postal_code": "06330-000"
        }
      },
      "installments": [
        {
          "installment": 1,
          "amount": 390.94,
          "date": "2020-11-13"
        },
        {
          "installment": 2,
          "amount": 390.94,
          "date": "2020-12-14"
        },
        {
          "installment": 3,
          "amount": 390.94,
          "date": "2021-01-13"
        },
        {
          "installment": 4,
          "amount": 390.94,
          "date": "2021-02-15"
        }
      ]
    },
    {
      "id": "231",
      "date": "2020-10-14",
      "amount": 2637,
      "customer": {
        "name": "Comprador 3",
        "address": {
          "street": "Avenida Cidade Jardim",
          "neighborhood": "Jardim Paulistano",
          "city": "São Paulo",
          "state": "SP",
          "postal_code": "01454-000"
        }
      },
      "installments": [
        {
          "installment": 1,
          "amount": 879,
          "date": "2020-11-16"
        },
        {
          "installment": 2,
          "amount": 879,
          "date": "2020-12-14"
        },
        {
          "installment": 3,
          "amount": 879,
          "date": "2021-01-14"
        }
      ]
    }
  ]
}
```
