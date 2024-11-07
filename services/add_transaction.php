<?php
// Inicia a sessão PHP para acessar variáveis de sessão (se necessário)
session_start();

// Inclui o arquivo que contém a classe API para usar os métodos de comunicação com a API
require_once '../utils/api.php';

// Define o tipo de conteúdo da resposta como JSON, para que o frontend consiga interpretar corretamente
header('Content-Type: application/json');

// Verifica se a requisição foi feita via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados enviados via POST (valor e descrição da transação)
    $value = $_POST['value'];
    $description = $_POST['description'];

    // Cria uma instância da classe API
    $api = new API();

    // Envia os dados para o endpoint de criação de transação via requisição POST
    $response = $api->post('/transaction/create', [
        'value' => $value,
        'description' => $description
    ]);

    // Verifica o status da resposta, se for 201 (criado com sucesso), retorna uma mensagem de sucesso
    if ($response['status'] === 201) {
        echo json_encode([
            'success' => true,  // Indica que a transação foi cadastrada com sucesso
            'message' => 'Transação cadastrada com sucesso!'  // Mensagem de sucesso
        ]);
    } else {
        // Caso contrário, retorna uma mensagem de erro com base na resposta da API
        echo json_encode([
            'success' => false,  // Indica que ocorreu um erro ao tentar cadastrar a transação
            'message' => $response['message'] ?? 'Erro ao cadastrar a transação'  // Mensagem de erro, se disponível
        ]);
    }
}
?>
