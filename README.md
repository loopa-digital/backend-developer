# Teste Backend Developer Loopa Digital

## Introdução

Esse projeto contém a solução para o teste postado em https://github.com/loopa-digital/backend-developer. O código foi desenvolvido utilizando-se o framework Lumen.

Além das funcionalidades solicitadas, foram implementados também testes unitários para o PHPUnit.

Nesse documento serão descritas instruções gerais de como executar esse projeto.

## Instalação do projeto

### Instalação do PHP e extensões necessárias

Esse projeto foi escrito no contexto do framework Lumen, versão 8.2.2. Para essa versão, conforme descrito em https://lumen.laravel.com/docs/8.x/installation, é necessário que você tenha instalado em sua máquina o PHP de linha de comando na versão 7.3, ou em uma versão maior, além das extensões OpenSSL, PDO e Mbstring do PHP.

Para garantir que a instalação dessas ferramentas está correta, execute o seguinte comando para verificar se o executável do PHP está acessível:

```
php --version
```

De forma similar, os módulos disponíveis podem ser visualizados executando-se o seguinte comando:

```
php -m
```

### Instalação do Composer

Para instalar as dependências do projeto, é necessário instalar o gerenciador de pacotes. O seguinte comando deverá trazer a versão instalada em sua máquina:

```
composer --version
```

### Instalação do Git

Primeiro, é necessário verificar se você possui o Git instalado em sua máquina. Comece executando o seguinte comando no terminal ou prompt de comando:

```
git --version
```

Se você obter uma mensagem indicando qual a versão do Git, isso significa que o mesmo está instalado em sua máquina. Se o comando não for encontrado, ele poderá não estar instalado, ou poderá estar instalado mas não acessível de qualquer diretório.

Quando você tiver certeza de que o Git está instalado em sua máquina e de que você consegue acessá-lo pelo terminal ou prompt de comando, você poderá fazer o clone do repositório.

### Clonagem do repositório

Faça uma cópia local do repositório utilizando o seguinte comando no terminal ou prompt de comando:

```
https://github.com/fernandokarpinski/backend-developer.git
```

Em seguida, ainda pelo terminal ou prompt de comando, entre na pasta backend-developer.

### Instalação das dependências pelo Composer

Instale as dependências do projeto através do seguinte comando:

```
composer install
```

### Criação do arquivo .env

Faça uma cópia do arquivo .env.example e a dê o nome de .env. Esse arquivo possui variáveis de ambiente utilizadas pelo Lumen, entre elas a uma variável cujo valor é a URL principal da API de CEPs utilizada para a busca de endereços.

### Execução dos testes unitários

Os testes unitários podem ser executados a partir da raiz do projeto, fazendo-se uma chamada ao arquivo binário do PHPUnit (que encontra-se dentro da pasta vendor).

### Execução do projeto

Para executar o projeto, inicie o servidor local do PHP executando o seguinte comando, a partir da raiz do projeto:

```
php -S localhost:8000 -t public
```

## Envio do arquivo para o processamento

Com o servidor sendo executando na porta 8000, o passo final consiste em se fazer uma requisição POST ao endereço localhost:8000/arquivo/processar, enviando-se um corpo no formato JSON, o qual deve possuir a seguinte estrutura:

```
{
	"arquivo": "MTIzMjAyMDEwMTIwMDAwMTExMzI3MDNDb21wcmFkb3IgMSAgICAgICAgIDA2MDUwMTkwCjMyMTIwMjAxMDEzMDAwMDE1NjM3NTA0Q29tcHJhZG9yIDIgICAgICAgICAwNjMzMDAwMAoyMzEyMDIwMTAxNDAwMDAyNjM4MDAwM0NvbXByYWRvciAzICAgICAgICAgMDE0NTQwMDA="
}
```

O valor da propriedade "arquivo" é o conteúdo do arquivo, codificado em base64. Ao receber essa informação, o sistema irá descriptografá-la e "quebrar" o conteúdo em linhas, e, sem seguida, processará uma linha por vez, enviando o resultado final ao cliente, também no formato JSON.

## Considerações finais

### Tratamento de erros

Quando o sistema encontra um erro de sintaxe ou de lógica em uma linha, ele imediatamente retorna uma mensagem de erro para o cliente. Esse foi um caminho escolhido para simplificar o tratamento de erros. Numa aplicação real, talvez fosse necessário que o sistema continuasse processando as linhas e, quando terminasse, retornasse as linhas processadas com sucesso e as linhas com erro.

### Diferenças de centavos

Se a soma das parcelas calculadas para uma compra não coincidir com o valor total da compra, é necessário fazer um ajuste na primeira parcela para que a diferença seja excluída ou adicionada a ela, de modo que a soma dos valores coincida com o valor total.

Por exemplo, se o valor total da venda for de R$ 2638,00 e a mesma for dividida em 3 parcelas, então, a princípio, o valor da parcela seria de R$ 2638 / 3 = R$ 879,33, com arredondamento para duas casas decimais. Porém, R$ 879,33 * 3 = R$ 2637,99, de modo que o cliente estaria pagando 1 centavo a menor do que o valor da compra. Para que o valor pago seja exatamente o esperado, adiciona-se a diferença 2638 - 2637,99 = 0,01 ao valor da primeira parcela, de modo que as parcelas 1, 2 e 3 passam a ser, respectivamente, de R$ R$ 879,34, R$ 879,33 e R$ 879,33, e a soma desses valores resulta no valor exato da compra.

Um outro cenário que pode ocorrer é de o cliente poder pagar a mais do que o valor da compra. Se o valor total da compra for de R$ 1563,75, e existirem 4 parcelas, então R$ 1563,75 / 4 = R$ 390,94. Porém, R$ 390,94 * 4 = R$ 1563,76, que é 1 centavo maior do que o valor esperado. Para esse tipo de cenário, o sistema subtrai essa diferença do valor da primeira parcela, resultando-se em 1 parcela de R$ 390,93 + 3 * R$ 390,94 = R$ 1563,75, que é exatamente o valor da compra.
