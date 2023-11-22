<?php
$nome = $_GET["nome"];
$passwd = $_GET["password"];
$user = $_GET["nickName"];
$cep = $_GET["cep"];

function get_endereco($cep){

    $cep = preg_replace("/[^0-9]/", "", $cep);
    $url = "http://viacep.com.br/ws/$cep/xml/";

    $xml = simplexml_load_file($url);
    return $xml;
  }
 if($_GET['cep']){

       $endereco = get_endereco($_GET["cep"]);
            $cep1 = $endereco->cep; 
            $localidade = $endereco->localidade; 
            $uf = $endereco->uf;
 } 

try {
    $host = "localhost";
    $database = "cedup";
    $username = "root";
    $password = "";

    $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $conn = new PDO($dsn, $username, $password, $options);

    $sql = "INSERT INTO usuarios (nome, login, senha, cep, localizacao, uf) VALUES (:nome, :login, :senha, :cep, :localidade, :uf)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':login', $user);
    $stmt->bindParam(':senha', $passwd);
    $stmt->bindParam(':cep', $cep1);
    $stmt->bindParam(':localidade', $localidade);
    $stmt->bindParam(':uf', $uf);

    if ($stmt->execute()) {
        error_log( "Novo registro inserido.");
    } else {
        echo "Erro: " . $stmt->errorInfo()[2];
    }

    $conn = null;
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE- edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: lime;
            color: DeepSkyBlue; 
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: SteelBlue;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .form-group {
            text-align: left;
        }

        .btn-custom {
            background-color: CadetBlue;
            border: none;
            color: LawnGreen;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .btn-custom:hover {
            background-color: CadetBlue;
        }
        p{
            color:LawnGreen;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seu registro foi Salvo!</h1>
        <p>Verificar Dados:</p><a href="./crud-localizar.php"><img style="width:30px; height:30px;" src="https://cdn-icons-png.flaticon.com/128/5253/5253968.png"></a>
        <p>Voltar:</p><a href="./crud-inicio.html"><img style="width:30px; height:30px;" src="https://cdn-icons-png.flaticon.com/128/3236/3236910.png"></a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>