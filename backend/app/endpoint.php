<?php
require_once 'routes.php';
require_once 'validator.php';

use Swoole\Http\Request;
use Swoole\Http\Response;

// Definindo os endpoints da API
function handleRequest(Request $request, Response $response)
{
  $response->header("Content-Type", "application/json");
  $response->header("Access-Control-Allow-Origin", "*");
  $response->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE");

  $router = new Router($request, $response);

  $router->get('/', function () use ($response) {
    $response->header('Content-Type', 'text/plain; charset=utf-8');
    $documentation = <<<DOC
    Seja bem-vindo à API! Aqui estão as rotas disponíveis:
  
    Rota principal ("/"):
      - Método: GET
      - Descrição: Retorna uma mensagem de boas-vindas à API.
    
    Rota de listar veículos ("/veiculos"):
      - Método: GET
      - Descrição: Retorna todos os veículos cadastrados.
      - Exemplo de uso: GET /veiculos
    
    Rota de busca de veículos ("/veiculos/find"):
      - Método: GET
      - Descrição: Realiza uma busca por veículos com base em um termo de pesquisa.
      - Parâmetros:
        - q (opcional): Termo de pesquisa para filtrar os veículos por marca ou nome do veículo.
      - Exemplo de uso: GET /veiculos/find?q=termo_de_pesquisa
    
    Rota de criação de veículo ("/veiculos"):
      - Método: POST
      - Descrição: Cria um novo veículo com os dados fornecidos.
      - Parâmetros:
        - marca: Marca do veículo (obrigatório)
        - veiculo: Nome do veículo (obrigatório)
        - ano: Ano de fabricação do veículo (obrigatório)
        - descricao: Descrição do veículo (obrigatório)
        - vendido: Indicador de venda do veículo (obrigatório)
      - Exemplo de uso: POST /veiculos
    
    Rota de atualização de veículo ("/veiculos"):
      - Método: PUT
      - Descrição: Atualiza os dados de um veículo existente com os dados fornecidos.
      - Parâmetros:
        - id: ID do veículo a ser atualizado (obrigatório)
        - marca: Marca do veículo (obrigatório)
        - veiculo: Nome do veículo (obrigatório)
        - ano: Ano de fabricação do veículo (obrigatório)
        - descricao: Descrição do veículo (obrigatório)
        - vendido: Indicador de venda do veículo (obrigatório)
      - Exemplo de uso: PUT /veiculos
    
  DOC;

    $response->end($documentation);
  });

  // Listar todos os veículos
  $router->get('/veiculos', function () use ($response) {
    try {
      $dbConnection = connect();
      $query = "SELECT * FROM veiculos ORDER BY id DESC";
      $stmt = $dbConnection->query($query);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $result = array_map(function ($row) {
        return array_map(function ($value) {
          return mb_convert_encoding($value, 'UTF-8');
        }, $row);
      }, $result);

      $return = array('status' => 'OK', 'code' => 200, 'data' => $result);
      $response->header('Content-Type', 'application/json');
      $response->end(json_encode($return));
    } catch (PDOException $e) {
      $response->status(500);
      $response->end("Erro no servidor: " . $e->getMessage());
    }
  });

  // Buscar veículos
  $router->get('/veiculos/find', function ($params) use ($request, $response) {
    try {
      $response->header('Content-Type', 'application/json');
      $searchTerm = $request->get['q'] ?? '';

      $dbConnection = connect();

      $statement = $dbConnection->prepare("SELECT * FROM veiculos WHERE marca LIKE ?");
      $statement->execute(["%" . $searchTerm . "%"]);
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);

      $result = array_map(function ($row) {
        return array_map(function ($value) {
          return mb_convert_encoding($value, 'UTF-8');
        }, $row);
      }, $result);

      if ($result) {
        $return = array('status' => 'OK', 'code' => 200, 'data' => $result);
        $response->header('Content-Type', 'application/json');
        $response->end(json_encode($return));
      } else {
        $response->status(404);
        $response->end("Nenhum veículo encontrado");
      }
    } catch (PDOException $e) {
      $response->status(500);
      $response->end("Erro no servidor: " . $e->getMessage());
    }
  });

  // Listar veículos por id
  $router->get('/veiculos/:id', function ($params) use ($request, $response) {
    try {
      $response->header('Content-Type', 'application/json');
      $id = $params['id'] ?? '';
      $dbConnection = connect();
      $statement = $dbConnection->prepare("SELECT * FROM veiculos WHERE id = ?");
      $statement->execute([$id]);
      $result = $statement->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        $result = array_map(function ($value) {
          return mb_convert_encoding($value, 'UTF-8');
        }, $result);

        $return = array('status' => 'OK', 'code' => 200, 'data' => $result);
        $response->end(json_encode($return));
      } else {
        $response->status(404);
        $response->end("Não encontrado");
      }
    } catch (PDOException $e) {
      $response->status(500);
      $response->end("Erro no servidor: " . $e->getMessage());
    }
  });

  // Deletar veículo por id
  $router->delete('/veiculos/:id', function ($params) use ($request, $response) {
    try {
      $response->header('Content-Type', 'application/json');
      $id = $params['id'] ?? '';

      $dbConnection = connect();
      $statement = $dbConnection->prepare("DELETE FROM veiculos WHERE id = ?");
      $statement->execute([$id]);

      if ($statement->rowCount() > 0) {
        $response->end(json_encode(array('status' => 'Delete', 'code' => 200)));
      } else {
        $response->status(404);
        $response->end("Não encontrado");
      }
    } catch (PDOException $e) {
      $response->status(500);
      $response->end("Erro no servidor: " . $e->getMessage());
    }
  });

  // Cadastrar veículos
  $router->post('/veiculos', function () use ($request, $response) {
    try {
      $newData = $request->getContent();
      $dbConnection = connect();
      $data = json_decode($newData, true);

      if (validateData($data)) {
        $statement = $dbConnection->prepare("INSERT INTO veiculos (marca, veiculo, ano, descricao, vendido, created) VALUES (?, ?, ?, ?, ?, ?)");
        $statement->execute([$data['marca'], $data['veiculo'], $data['ano'], $data['descricao'], $data['vendido'], date('Y-m-d H:i:s')]);

        $response->header('Content-Type', 'application/json');
        $response->end(json_encode(array('status' => 'Create', 'code' => 201)));
      } else {
        $response->status(400);
        $response->end(json_encode(array('status' => 'Invalid data', 'code' => 400)));
      }
    } catch (PDOException $e) {
      $response->status(500);
      $response->end("Erro no servidor" . $e->getMessage());
    }
  });

  // Editar veículos
  $router->put('/veiculos/:id', function ($params) use ($request, $response) {
    try {
      $response->header('Content-Type', 'application/json');
      $id = $params['id'] ?? '';

      $newData = $request->getContent();
      $dbConnection = connect();
      $data = json_decode($newData, true);

      if (validateData($data)) {
        $statement = $dbConnection->prepare("UPDATE veiculos SET marca=?, veiculo=?, ano=?, descricao=?, vendido=? WHERE id=?");
        $statement->execute([$data['marca'], $data['veiculo'], $data['ano'], $data['descricao'], $data['vendido'], $id]);

        $response->header('Content-Type', 'application/json');
        $response->end(json_encode(array('status' => 'Update', 'code' => 201)));
      } else {
        $response->status(400);
        $response->end(json_encode(array('status' => 'Invalid data', 'code' => 400)));
      }
    } catch (PDOException $e) {
      $response->status(400);
      $response->end(json_encode(array('status' => 'Bad Request', 'code' => 400)));
    }
  });

  // Caso as rotas não seja encontradas
  $router->notFound(function () use ($response) {
    $response->status(404);
    $response->end(json_encode(array('status' => 'Not found', 'code' => 404)));
  });

  $router->execute();
}
