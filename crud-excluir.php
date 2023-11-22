<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];

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

        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Sucesso ao excluir";
        } else {
            echo "Erro na exlusão, tente novamente " . $stmt->errorInfo()[2];
            http_response_code(400);
        }

        $conn = null;
    } catch (PDOException $e) {
        die("Erro ao conectar com Banco de Dados " . $e->getMessage());
    }
} else {
    http_response_code(400);
    echo "Protocolo não disponivel pra essa situação";
}
?>