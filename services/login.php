<?php
// Inicia a sessão PHP para armazenar variáveis de sessão
session_start();

// Inclui o arquivo que contém a classe API para interação com a API externa
require_once '../utils/api.php';

// Define o tipo de conteúdo da resposta como JSON, para que o frontend possa interpretar adequadamente
header('Content-Type: application/json');

// Verifica se a requisição HTTP é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adiciona espera de 2s apenas para exibi o spinn de loader na tela de login
    sleep(2);

    // Obtém os valores de email e senha enviados via POST
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cria uma nova instância da classe API para fazer a requisição de login
    $api = new API();

    // Chama o método login da classe API, passando os dados de email e senha
    $loginResponse = $api->login($email, $password);

    // Verifica se a resposta da API foi bem-sucedida (login correto)
    if ($loginResponse['success']) {
        // Se o login for bem-sucedido, envia uma resposta JSON com sucesso
        echo json_encode([
            'success' => true,
            'message' => 'Login bem-sucedido',  // Mensagem de sucesso
        ]);
    } else {
        // Caso o login falhe, envia uma resposta JSON com erro e a mensagem de erro da API
        echo json_encode([
            'success' => false,
            'status' => $loginResponse['status'],  // Status de erro retornado pela API
            'message' => $loginResponse['message'], // Mensagem de erro retornada pela API
        ]);
    }
}
?>
