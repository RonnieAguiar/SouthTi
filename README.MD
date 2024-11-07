# Projeto SouthTi
O prosente projeto é um desafio de implementação de aplicação usando HTML, CSS, JavaScript, jQuery e PHP.

## Executar o projeto
Com PHP instalado basta rodar o comando abaixo na raiz do projeto, alterando a porta caso deseje.

    php -S localhost:3000

Por conter chamadas cURL a api HTTPS, é importante que a instalação do PHP, em php.ini, contenha indicação do certificao cacert.pem ou seja configurado para ignorar.

## Index.html
A aplicação tem como início o index.html que traz o conceito de SPA tendo como ponto de inserção.

    <div id="app"></div>

## Login
Esta tela possue validação por script se o input email é um e-mail valido e se password possui mínimo de 6 digitos.

Ao efetuar login, caso senha esteja incorreta a mensagem original é alterada para exibir a mensagem retornada pela API.

## Home
Esta tela possui formulário para inserir nova transação. Obtendo sucesso, um alert é exibido e a lista de transações é atualizada.

O input value possui script para permitir entrada numérica, inclusive negativa, com dois dígitos decimais separados por virgula.

Uma vez feito login, o refresh da aplicação direciona a Home, não solicitando novo login até que se perca a sessão, o que ocorre com o encerramento do navegador.
