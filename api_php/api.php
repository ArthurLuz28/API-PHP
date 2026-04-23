<?php

    //CABEÇALHO
    header("Content-Type: application/json"); // Define o tipo de resposta

    $metodo = $_SERVER['REQUEST_METHOD'];
    // echo "Método da requisição: " . $metodo;

    // RECUPERA O ARQUIVO JSON NA MESMA PASTA DO PROJETO
    $arquivo = 'usuarios.json';

    // VERIFICAR SE O ARQUIVO EXISTE, SE NÃO EXISTIR CRIA UM COM ARRAY VAZIO
    if (!file_exists($arquivo)) {
        file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // LÊ O CONTEÚDO DO ARQUIVO JSON
    $usuarios = json_decode(file_get_contents($arquivo), true);

    //CONTEÚDO
    // $usuarios = [
    // ["id" => 1, "nome" => "Maria Souza", "email" => "maria@email.com"],
    // ["id" => 2, "nome" => "João Silva", "email" => "joao@email.com"]
    // ];

    switch ($metodo) {
        case 'GET':
            // echo "AQUI AÇÕES DO MÉTODO GET";
            // Converte para JSON e retorna
            echo json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        case 'POST':
            // echo "AQUI AÇÕES DO MÉTODO POST";
            // Ler os dados no corpo da requisição
            $dados = json_decode(file_get_contents("php://input"), true);
            // print_r($dados);
            
            // Verifica se os campos obrigatórios foram preenchidos
            if (!isset($dados["id"]) || !isset($dados["nome"]) || !isset($dados["email"])) {
                http_response_code(400);
                echo json_encode(["erro" => "Dados incompletos."], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Cria novo usuário
            $novoUsuario = [
                "id" => $dados["id"],
                "nome" => $dados["nome"],
                "email" => $dados["email"]
            ];

            //Adiciona ao array de usuários
            $usuarios[] = $novo_usuario;

            //Salva o array atualizado no arquivo json
            file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            //Retorna mensagem de sucesso
            echo json_encode(["mensagem" => "Usuário inserido com sucesso!", "usuarios" => $usuarios], JSON_UNESCAPED_UNICODE);
            break;

            // Adiciona o novo usuário ao array existente
            // array_push($usuarios, $novoUsuario);
            // echo json_encode('Usuário inserido com sucesso!');
            // print_r($usuarios);

            break;

        default:
            // echo "MÉTODO NÃO ENCONTRADO!";
            // break;
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido!"], JSON_UNESCAPED_UNICODE);
            break;
    }

?>