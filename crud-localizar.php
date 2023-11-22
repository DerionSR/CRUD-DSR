<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Usuários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .modal-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: DarkSlateGray;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    $username = "root";
    $password = "";

    try {
        $conn = new PDO('mysql:host=localhost;dbname=cedup', $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $conn->query('SELECT * FROM usuarios');
        echo '<div class="container">';
        echo '<table id="tabela01" class="table table-striped table-bordered">';
        echo ' <thead>';
        echo '<tr><th>Id</th><th>Nome</th><th>Usuario</th><th>Senha</th><th>Cep</th><th>Localidade</th><th>Uf</th><th>Acoes</th></tr>';
        echo ' </thead>';

        foreach($data as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row["id"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["nome"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["login"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["senha"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["cep"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["localizacao"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["uf"]) . '</td>';
            echo '<td>
                    <a href="#"><img onclick="excluir('.$row["id"].')" style="width:30px; height:30px;" src="https://cdn-icons-png.flaticon.com/128/2602/2602735.png"></a>
                    <a href="#" onclick="abrirDivFlutuante(' . $row["id"] . ', \'' . htmlspecialchars($row["nome"]) . '\', \'' . htmlspecialchars($row["login"]) . '\', \'' . htmlspecialchars($row["senha"]) . '\', \'' . htmlspecialchars($row["cep"]) . '\')">
                        <img style="width:30px; height:30px;" src="https://cdn-icons-png.flaticon.com/128/9268/9268098.png">
                    </a>
                </td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';

    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    ?>
</div>

<div class="modal-container" id="divFlutuante">
    <div class="modal-content">
        <form id="editarForm">
            <input type="hidden" id="editUserId" name="editUserId">
            <div class="form-group">
                <label for="editNome">Nome:</label>
                <input type="text" class="form-control" id="editNome" name="editNome">
            </div>
            <div class="form-group">
                <label for="editUsuario">Usuário:</label>
                <input type="text" class="form-control" id="editUsuario" name="editUsuario">
            </div>
            <div class="form-group">
                <label for="editSenha">Senha:</label>
                <input type="password" class="form-control" id="editSenha" name="editSenha">
            </div>
            <div class="form-group">
                <label for="editCep">Cep:</label>
                <input type="text" class="form-control" id="editCep" name="editCep">
            </div>
            <button type="button" onclick="salvarEdicao()">Salvar</button>
            <button type="button" onclick="fecharDivFlutuante()">Fechar</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function abrirDivFlutuante(id, nome, usuario, senha, cep) {
        $('#editUserId').val(id);
        $('#editNome').val(nome);
        $('#editUsuario').val(usuario);
        $('#editSenha').val(senha);
        $('#editCep').val(cep);
        $('#divFlutuante').show();
    }

    function fecharDivFlutuante() {
        $('#divFlutuante').hide();
    }

    function salvarEdicao() {
        var userId = $('#editUserId').val();
        var novoNome = $('#editNome').val();
        var novoUsuario = $('#editUsuario').val();
        var novaSenha = $('#editSenha').val();
        var cep = $('#editCep').val();

        var dados = {
            'id': userId,
            'nome': novoNome,
            'usuario': novoUsuario,
            'senha': novaSenha,
            'cep': cep
        };

        $.ajax({
            type: 'POST',
            url: 'crud-atualizar.php',
            data: dados,
            success: function(response) {
                if (response === 'success') {
                    alert('Erro ao excluir');
                    fecharDivFlutuante();
                } else {
                    alert('Edição salva com sucesso!');
                    fecharDivFlutuante();
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na solicitação AJAX: ' + error);
            }
        });
    }

    function excluir(userId) {
        const data = {
            'id': userId
        };

        $.ajax({
            url: 'crud-excluir.php',
            type: 'POST',
            data: data,
            success: function(result) {
                alert("Excluído com sucesso");
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Erro ao excluir");
            }
        });
    }
</script>

<style>
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: Lime;
    }

    td {
        color: Aqua;
        font-weight: bold;
    }

    th {
        color: SteelBlue;
        font-weight: bold;
        font-size: 30px;
    }

    p {
        color: DeepSkyBlue;
        font-weight: bold;
        font-size: 30px;
    }

    a {
        background-color: Lime;
        border: solid black 2px;
    }
</style>
</body>
</html>
