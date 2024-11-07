<?php
// Define a classe API, que facilita a comunicação com a API externa
class API
{
    // URL base da API que será utilizada nas requisições
    private $apiUrl = "https://southti.com.br/desafio-front-end";

    // Token fixo para autenticação, utilizado em algumas requisições
    private $fixedToken = "8A9B362F-E744-4BEE-8031-39A23FA67E63";

    // Método para realizar o login do usuário, passando email e senha
    public function login($email, $password)
    {
        // URL do endpoint de autenticação
        $url = $this->apiUrl . "/user/auth";

        // Dados do login que serão enviados via POST
        $data = [
            'email' => $email,
            'password' => $password,
        ];

        // Chama a função makePostRequest para fazer a requisição de login
        $response = $this->makePostRequest($url, $data, true);

        // Verifica se o token foi retornado na resposta (login bem-sucedido)
        if (isset($response['token'])) {
            // Salva o token de autenticação na sessão
            $_SESSION['user_token'] = $response['token'];

            // Retorna o resultado do login com sucesso
            return [
                'status' => 200,
                'success' => true,
                'message' => 'Login bem-sucedido',
                'token' => $response['token']
            ];
        }

        // Retorna erro caso não tenha o token na resposta
        return [
            'status' => $response['status'] ?? 500,
            'success' => false,
            'message' => $response['message'] ?? 'Erro desconhecido ao fazer login'
        ];
    }

    // Método para enviar uma requisição POST para a API
    public function post($endpoint, $data)
    {
        // Cria a URL completa para a requisição POST
        $url = $this->apiUrl . $endpoint;

        // Chama a função makePostRequest para fazer a requisição POST
        return $this->makePostRequest($url, $data);
    }

    // Método para enviar uma requisição GET para a API
    public function get($endpoint)
    {
        // Cria a URL completa para a requisição GET
        $url = $this->apiUrl . $endpoint;

        // Chama a função makeGetRequest para fazer a requisição GET
        return $this->makeGetRequest($url);
    }

    // Função interna para fazer requisições POST
    private function makePostRequest($url, $data, $useFixedToken = false)
    {
        // Inicializa a requisição cURL
        $ch = curl_init($url);

        // Define que a resposta deve ser retornada como string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Define que a requisição será do tipo POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Adiciona os dados da requisição no formato x-www-form-urlencoded
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        // Define os cabeçalhos da requisição, incluindo o token de autenticação
        $headers = [
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: " . ($useFixedToken ? $this->fixedToken : $_SESSION['user_token'])
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Executa a requisição cURL
        $response = curl_exec($ch);

        // Obtém o código HTTP da resposta
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Verifica se houve erro na requisição cURL
        $curlError = curl_error($ch);

        // Fecha a conexão cURL
        curl_close($ch);

        // Se houver erro na requisição, retorna um erro com a mensagem
        if ($curlError) {
            return [
                'status' => 500,
                'success' => false,
                'message' => "Erro de conexão com a API: $curlError"
            ];
        }

        // Decodifica a resposta JSON para um array associativo
        $result = json_decode($response, true);

        // Adiciona o código HTTP no resultado da resposta
        $result['status'] = $httpCode;

        return $result;
    }

    // Função interna para fazer requisições GET
    private function makeGetRequest($url)
    {
        // Inicializa a requisição cURL
        $ch = curl_init($url);

        // Define que a resposta deve ser retornada como string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Define os cabeçalhos da requisição, incluindo o token de autenticação
        $headers = [
            "Authorization: " . $_SESSION['user_token']  // Utiliza o token da sessão para autenticação
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Executa a requisição cURL
        $response = curl_exec($ch);

        // Obtém o código HTTP da resposta
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Fecha a conexão cURL
        curl_close($ch);

        // Verifica se houve erro na requisição
        if ($response === false) {
            return [
                'status' => 500,
                'success' => false,
                'message' => 'Erro ao conectar com a API'
            ];
        }

        // Retorna o status da requisição e os dados da resposta
        return [
            'status' => $httpCode,
            'data' => $response
        ];
    }
}
?>
