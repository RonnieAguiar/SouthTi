<?php
// Inicia a sessão PHP para acessar variáveis de sessão
session_start();

// Define o tipo de conteúdo da resposta como JSON, para que o frontend consiga interpretar corretamente a resposta
header('Content-Type: application/json');

// Verifica se a variável de sessão 'user_token' está definida (indicando que o usuário está logado)
if (isset($_SESSION['user_token'])) {
    // Se o usuário estiver logado, retorna um JSON com a chave 'logged_in' como true e uma mensagem de sucesso
    echo json_encode([
        'logged_in' => true,
        'message' => 'Usuário está logado',  // Mensagem informando que o usuário está autenticado
    ]);
} else {
    // Se a variável de sessão 'user_token' não estiver definida, retorna um JSON com a chave 'logged_in' como false e uma mensagem de erro
    echo json_encode([
        'logged_in' => false,
        'message' => 'Usuário não está logado',  // Mensagem informando que o usuário não está autenticado
    ]);
}
?>
