$(document).ready(function () {
    // Mensagem original de login
    const originalLoginMessage = 'Olá!<br>Entre com seu login e senha para começar.';

    // Função para validar um e-mail usando expressão regular simples
    function isValidEmail(email) {
        // Expressão regular para validação de e-mail simples
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return emailPattern.test(email);
    }

    // Função para atualizar a mensagem de erro de login
    function setLoginMessageError(error, customMessage = '') {
        if (error) {
            $('.login-message').addClass('text-danger');  // Adiciona a classe de erro
            $('.login-message').html(customMessage || 'Ops,<br>Invalid email or password!');
        } else {
            $('.login-message').removeClass('text-danger');  // Remove a classe de erro
            $('.login-message').html(originalLoginMessage);  // Restaura a mensagem original
        }
    }

    // Função para carregar uma visão específica no elemento #app
    function loadView(view) {
        $("#app").load(`view/${view}.php`);
    }

    // Função para buscar transações do servidor e exibi-las na interface
    function getTransactions() {
        $.get("services/get_transactions.php", function (response) {
            if (response.success) {
                $('.timeline').empty();  // Limpa a lista de transações

                // Para cada transação, cria-se o HTML correspondente
                response.data.forEach(transaction => {
                    let description = transaction.description;
                    let value = parseFloat(transaction.value);

                    // Define a classe do valor com base se é positivo ou negativo
                    let valueClass = value < 0 ? 'amount negative' : 'amount positive';

                    // HTML para a transação
                    let transactionHTML = `
                        <div class="transaction">
                            <div class="bullet"></div>
                            <div class="transaction-info">
                                <p class="${valueClass}">${value < 0 ? '- ' : '+ '}R$${Math.abs(value).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</p>
                                <p class="description">${description}</p>
                            </div>
                        </div>
                    `;

                    // Adiciona a transação à lista
                    $('.timeline').append(transactionHTML);
                });
            } else {
                console.error("Erro ao carregar transações:", response.message);  // Exibe erro no console se falhar
            }
        });
    }

    // Verifica qual view precisa ser exibida
    async function checkLogin() {
        $.get("services/check_login.php", async function (response) {
            if (response.logged_in) {
                loadView("home");
                getTransactions();
            } else {
                loadView("login");
            }
        }, "json").fail(function () {
            $(".login-message").html("Erro de conexão com o servidor.");
        });
    }
    checkLogin();  // Verifica o login ao carregar a página

    // Validação do e-mail ao mudar o campo
    $(document).on('change', 'input[name="email"]', function () {
        const value = $(this).val().trim();

        $(this).removeClass('input-success input-danger');  // Remove as classes de validação anteriores
        $(this).next('.validation-icon').remove();  // Remove o ícone de validação

        if (isValidEmail(value)) {
            setLoginMessageError(false);  // Se o e-mail for válido, remove a mensagem de erro
            $(this).addClass('input-success');  // Adiciona a classe de sucesso
        } else {
            setLoginMessageError(true);  // Se inválido, exibe erro
            $(this).addClass('input-danger');  // Adiciona a classe de erro
        }
    });

    // Validação da senha ao mudar o campo
    $(document).on('change', 'input[name="password"]', function () {
        const value = $(this).val().trim();

        $(this).removeClass('input-success input-danger');  // Remove as classes de validação anteriores
        $(this).next('.validation-icon').remove();  // Remove o ícone de validação

        if (value.length >= 6) {
            setLoginMessageError(false);  // Se a senha for válida, remove a mensagem de erro
            $(this).addClass('input-success');  // Adiciona a classe de sucesso
        } else {
            setLoginMessageError(true);  // Se inválida, exibe erro
            $(this).addClass('input-danger');  // Adiciona a classe de erro
        }
    });

    // Formatação do valor (para transações)
    $(document).on('input', 'input[name="value"]', function() {
        // Remove caracteres não numéricos, exceto vírgula e hífen
        let value = $(this).val().replace(/[^-\d,]/g, '');

        // Separa a parte inteira da decimal
        let parts = value.split(',');

        // Verifica se o primeiro caractere é um sinal de menos
        let isNegative = parts[0].startsWith('-');

        // Remove o sinal de menos antes de converter para número
        if (isNegative) {
            parts[0] = parts[0].slice(1);
        }

        // Converte a parte inteira para número e remove zeros à esquerda
        if (parts[0].length > 0) {
            parts[0] = parseInt(parts[0], 10).toString();
        }

        // Limita a parte decimal a duas casas
        if (parts.length > 1) {
            parts[1] = parts[1].slice(0, 2);
        }

        // Reconstroi o valor final
        $(this).val((isNegative ? '-' : '') + parts.join(','));
    });

    // Submissão do formulário de login
    $(document).on("submit", "#login-form", function (e) {
        e.preventDefault();  // Impede o comportamento padrão de submissão

        // Exibe spinner e oculta o botão
        const button = $(this).find("button[type='submit']");
        const loader = $(this).find("span");
        button.css("display", "none");
        loader.css("display", "flex");

        const email = $('input[name="email"]').val().trim();
        const password = $('input[name="password"]').val().trim();

        // Verificação extra para garantir que os campos foram validados
        if (!isValidEmail(email) || password.length <= 6) {
            setLoginMessageError(true);
            return;
        }

        // Realiza a chamada para login
        const formData = $(this).serialize();

        $.post("services/login.php", formData, function (response) {
            if (response.success) {
                checkLogin();  // Verifica novamente o login após a resposta
            } else {
                setLoginMessageError(true, response.message || "Erro desconhecido");
            }

            // Retorna com o botão
            button.css("display", "block");
            loader.css("display", "none");
        }, "json").fail(function (jqXHR) {
            const response = jqXHR.responseJSON;
            const errorMessage = response && response.message ? response.message : "Erro de conexão com o servidor.";
            setLoginMessageError(true, errorMessage);

            // Retorna com o botão
            button.css("display", "block");
            loader.css("display", "none");
        });

    });

    // Submissão do formulário de adição de transação
    $(document).on('submit', '.add-transaction form', function (e) {
        e.preventDefault();  // Impede o comportamento padrão de submissão

        // Coleta dos dados do formulário e garante o formato de ponto para decimal
        let rawValue = $(this).find('input[name="value"]').val();
        let value = rawValue.replace(',', '.');

        const formData = {
            value: value,
            description: $(this).find('textarea[name="description"]').val()
        };

        $.post("services/add_transaction.php", formData, function (response) {
            if (response.success) {
                alert(response.message);
                getTransactions();  // Atualiza a lista de transações
                $('.add-transaction form')[0].reset();  // Reseta o formulário
            } else {
                alert("Erro: " + response.message);
            }
        }, "json");
    });
});
