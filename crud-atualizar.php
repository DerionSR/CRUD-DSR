<?php

$host = "localhost";
$database = "cedup";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_POST['id'];
    $novoNome = $_POST['nome'];
    $novoUsuario = $_POST['usuario'];
    $novaSenha = $_POST['senha'];
    $cep = $_POST['cep'];


    $sql = "UPDATE usuarios SET nome = :nome, login = :login, senha = :senha, cep = :cep, localizacao = :localidade, uf = :uf WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $novoNome);
    $stmt->bindParam(':login', $novoUsuario);
    $stmt->bindParam(':senha', $novaSenha);


    if (!empty($cep)) {
        $endereco = get_endereco($cep);
        $cep1 = $endereco->cep;
        $localidade = $endereco->localidade;
        $uf = $endereco->uf;
        $stmt->bindParam(':cep', $cep1);
        $stmt->bindParam(':localidade', $localidade);
        $stmt->bindParam(':uf', $uf);
    } else {
        $stmt->bindParam(':cep', null, PDO::PARAM_NULL);
        $stmt->bindParam(':localidade', null, PDO::PARAM_NULL);
        $stmt->bindParam(':uf', null, PDO::PARAM_NULL);
    }

    if ($stmt->execute()) {
        echo 'Atualização realizada com sucesso';
    } else {
        echo 'Erro na tentativa de atualizar';
    }
} catch (PDOException $e) {
    echo 'Erro na conexão ao Banco de Dados';
}

function get_endereco($cep) {
    $cep = preg_replace("/[^0-9]/", "", $cep);
    $url = "http://viacep.com.br/ws/$cep/xml/";
    $xml = simplexml_load_file($url);
    return $xml;
}
?>