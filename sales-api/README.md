# Sales Upload API

Esta API tem como objetivo processar um arquivo de vendas em txt retornando os compradores e as parcelas das compras.

## Pré requisitos

Para instalar projeto é preciso somente instalar o Docker. Tutoriais de instalação para todos os SOs estão descritos no site oficial do [Docker](https://www.docker.com/get-started).
Para fazer os testes é preciso ter o aplicativo [Postman](https://www.postman.com/) ou [Insomnia](https://insomnia.rest/download/) para simular o frontend com a rota de envio do arquivo txt
## Instalação do projeto

Com o docker instalado e configurado, para "subir" o projeto basta entrar na pasta raiz e executar o seguinte comando:

```
docker-compose up -d
```

Após o término da instalação, execute o seguinte comando para instalar as dependências do projeto:

```
docker exec -it sales-api-php sh -c "cd sales-api && composer install"
```
## Testando o projeto

A API possui 2 endpoints:

- Index: api/v1 (GET);
- Upload Sales: api/v1/upload-sales (POST);

A 1a rota mostra uma pequena descrição da API. A 2a rota é onde é feito o upload do arquivo txt.
Para fazer o teste, no aplicativo Postman coloque a url 0.0.0.0:8787/api/v1/upload-sales e o configure da segunte forma:

- Na aba "body" escolha no combobox abaixo a opção form-data;
- Em seguida, na coluna key escolha a opção "file" e escreva o nome da variável que o backend esperaque é 'sales'.
- Na coluna value, apareceu um botão 'select files'. Clique nele e procure o arquivo 'sales.txt' no computador.
- Após isso clique em "Send".

Se tudo deu certo a resposta esperada será essa:

```json
[
    {
        "sales": {
            "id": 123,
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
                    "date": "2020-12-12"
                },
                {
                    "installment": 3,
                    "amount": 371.09,
                    "date": "2021-01-12"
                }
            ]
        }
    },
    {
        "sales": {
            "id": 321,
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
                    "date": "2020-12-13"
                },
                {
                    "installment": 3,
                    "amount": 390.94,
                    "date": "2021-01-13"
                },
                {
                    "installment": 4,
                    "amount": 390.93,
                    "date": "2021-02-13"
                }
            ]
        }
    },
    {
        "sales": {
            "id": 231,
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
                    "date": "2020-11-14"
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
    }
]
```

## Licença

Esta API está licenciada sob a [Licença MIT](https://opensource.org/licenses/MIT).
