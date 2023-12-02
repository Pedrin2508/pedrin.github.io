<?php
// Inclua o arquivo de conexão
include __DIR__ . '/Conexao.php';

// Processar a exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    $idExcluir = $_POST['excluir_id'];

    try {
        $conexao = Conexao::getConexao();

        $sqlExcluir = "DELETE FROM assinaturas WHERE id = :id";
        $stmtExcluir = $conexao->prepare($sqlExcluir);
        $stmtExcluir->bindParam(':id', $idExcluir, PDO::PARAM_INT);

        if ($stmtExcluir->execute()) {
            echo "<p>Assinatura excluída com sucesso!</p>";
        } else {
            echo "<p>Erro ao excluir a assinatura.</p>";
        }
    } catch (PDOException $e) {
        echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    }
}

// Consultar assinaturas no banco de dados
try {
    $conexao = Conexao::getConexao();

    $sql = "SELECT id, nome, assinatura, data_registro FROM assinaturas";
    $result = $conexao->query($sql);

    $assinaturas = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro na consulta ao banco de dados: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinatura Digital - Tabela</title>
    <link rel="stylesheet" type="text/css" href="caminho/para/signature_pad.css">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .assinatura-img {
            max-width: 100px; /* Ajuste o tamanho máximo conforme necessário */
            max-height: 50px; /* Ajuste o tamanho máximo conforme necessário */
        }

        .voltar-btn {
            display: block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php
    // Exibir a tabela de assinaturas
    if (isset($assinaturas) && !empty($assinaturas)) {
        echo "<h2>Assinaturas Registradas</h2>";
        echo "<table>";

        // Exibindo cabeçalho
        echo "<tr><th>ID</th>";
        echo "<th>Nome</th>";
        echo "<th>Assinatura</th>";
        echo "<th>Data de Registro</th>"; // Nova coluna para a data
        echo "<th>Excluir</th>"; // Nova coluna para a exclusão
        echo "</tr>";

        // Exibindo dados
        foreach ($assinaturas as $assinatura) {
            echo "<tr>";
            echo "<td>{$assinatura['id']}</td>";
            echo "<td>{$assinatura['nome']}</td>";
            echo "<td><img class='assinatura-img' src='{$assinatura['assinatura']}' alt='Assinatura'></td>";
            echo "<td>" . date('d/m/Y H:i:s', strtotime($assinatura['data_registro'])) . "</td>";
            echo "<td>";
            echo "<form method='post' onsubmit='return confirm(\"Tem certeza que deseja excluir?\")'>";
            echo "<input type='hidden' name='excluir_id' value='{$assinatura['id']}'>";
            echo "<button type='submit'>Excluir</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";

        // Botão Voltar
        echo "<a class='voltar-btn' href='assinatura.php'>Voltar</a>";
    } else {
        echo "<p>Nenhuma assinatura registrada ainda.</p>";
    }
    ?>

</body>
</html>
