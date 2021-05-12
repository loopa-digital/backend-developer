# Test Backend Developer Loopa Digital

## Como usar
### Execute o comando para instalar as dependencias 
 composer install
 
### Execute o comando para iniciar o servidor
 php -S localhost:8080 -t public


### Envie somente arquivos txt contendo a seguinte estrutura:
    Arquivo de exemplo *sales.txt* está incluso no repositõrio

    ```
    12320201012000011132703Comprador 1         06050190
    32120201013000015637504Comprador 2         06330000
    23120201014000026370003Comprador 3         01454000
    ```
### Exemplo de resposta da API

Usando de exemplo a terceira linha `23120201014000026370003Comprador 3         01454000`, a resposta da API deve seguir o seguinte formato.

```JSON
{
    "sales": [
        {
            "id": 231,
            "date": "2020-10-14",
            "amount": 2638.00,
            "customer": {
                "name": "Comprador 3",
                "address": {
                    "street": "Av Cidade Jardin",
                    "neighborhood": "Jardim Paulistano",
                    "city": "Sâo Paulo",
                    "state": "SP",
                    "postal_code": "01454-000"
                }
            },
            "installments": [
                {
                    "installment": 1,
                    "amount": 879.34,
                    "date": "2020-11-16"
                },
                {
                    "installment": 2,
                    "amount": 879.33,
                    "date": "2020-12-14"
                },
                {
                    "installment": 3,
                    "amount": 879.33,
                    "date": "2021-01-14"
                }
            ]
        }
    ]
}
```
