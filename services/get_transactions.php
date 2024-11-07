<?php
// Inicia a sessão PHP para armazenar variáveis de sessão, caso necessário
session_start();

// Inclui o arquivo que contém a classe API, responsável por interagir com a API externa
require_once '../utils/api.php';

// Define o tipo de conteúdo da resposta como JSON, para que o frontend consiga interpretar a resposta corretamente
header('Content-Type: application/json');

// Verifica se a requisição HTTP é do tipo GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Cria uma nova instância da classe API para fazer a requisição de transações
    $api = new API();

    // Chama o método get da classe API para buscar a lista de transações
    $response = $api->get('/transaction/list');

    // Verifica se a chave 'data' está presente na resposta e se ela é uma string
    if (isset($response['data']) && is_string($response['data'])) {
        // Tenta decodificar o conteúdo JSON retornado pela API
        $decodedResponse = json_decode($response['data'], true);

        // Verifica se a decodificação do JSON foi bem-sucedida
        if ($decodedResponse === null) {
            // Se a decodificação falhar, retorna uma mensagem de erro com a descrição do problema
            echo json_encode([
                'success' => false,
                'message' => "Erro ao decodificar a resposta JSON: " . json_last_error_msg()
            ]);
        } else {
            // Se a decodificação for bem-sucedida, retorna os dados das transações em formato JSON
            echo json_encode([
                'success' => true,
                'data' => $decodedResponse  // Dados das transações retornados pela API
            ]);
        }
    } else {
        // Se a resposta da API não contiver os dados esperados, retorna uma mensagem de erro
        echo json_encode([
            'success' => false,
            'message' => "Erro ao obter resposta da API"  // Mensagem de erro genérica se a API não retornar o esperado
        ]);
    }
}
?>
