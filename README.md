# Test Backend Developer Loopa Digital

## Como usar
### Execute o comando para instalar as dependências 
 composer install
 
### Execute o comando para iniciar o servidor
 php -S localhost:8080 -t public
 
### Rota para acessar o interpretador
Acessar http://localhost:8080/customer/interpreter

### Enviar arquivo no formulário prenchendo campo com customerTxt
Segue exemplo requisição:
![image](https://user-images.githubusercontent.com/34348609/118025281-a77fc180-b32d-11eb-9d1b-768299c06594.png)

### Envie somente arquivos txt contendo a seguinte estrutura:
    Arquivo de exemplo *sales.txt* está incluso no repositório

    ```
    12320201012000011132703Comprador 1         06050190
    32120201013000015637504Comprador 2         06330000
    23120201014000026370003Comprador 3         01454000
    ```
### Exemplo de resposta da API

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
