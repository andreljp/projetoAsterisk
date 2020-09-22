<?php

require_once '../config/conexao.php';


if (!isset($_GET['acao'])) $acao = "listar";
else $acao = $_GET['acao'];

/**
 * Ação de listar
 */
if ($acao == "listar") {
    $sql = "SELECT
   r.id,
   r.name,
   r.username,
   r.secret,
   r.context,
   g.nome AS grupo
FROM
   ramais_sip r
   INNER JOIN
      grupo g
      ON g.id = r.id_grupo
ORDER BY
   r.name ASC";


    $query    = $con->query($sql);
    $registros = $query->fetchAll();

    require_once '../template/cabecalho.php';
    require_once 'lista_ramais.php';
    require_once '../template/rodape.php';
}

/**
 * Ação Novo
 **/
else if ($acao == "novo") {

    $lista_grupo = getGrupos();
    //print_s($lista_grupo);
    require_once '../template/cabecalho.php';
    require_once 'form_ramais.php';
    require_once '../template/rodape.php';
}

/**
 * Ação Gravar
 **/
else if ($acao == "gravar") {
    $registro = $_POST;

    //var_dump($registro);
    $sql    = "INSERT INTO ramais_sip(name, username, secret, context, id_grupo) VALUES(:name, :username, :secret, :context, :id_grupo)";
    $query  = $con->prepare($sql);
    $result = $query->execute($registro);
    if ($result) {
        header('Location: ./ramais.php');
    } else {
        echo "Erro ao tentar inserir o ramal";
    }
}
/**
 * Ação Excluir
 **/
else if ($acao == "excluir") {
    $id    = $_GET['id'];
    $sql   = "DELETE FROM ramais_sip WHERE id = :id";
    $query = $con->prepare($sql);

    $query->bindParam(':id', $id);

    $result = $query->execute();
    if ($result) {
        header('Location: ./ramais.php');
    } else {
        echo "Erro ao tentar remover o ramal de id: " . $id;
    }
}
/**
 * Ação Atualizar
 **/
else if ($acao == "buscar") {
    $lista_grupo = getGrupos();
    $id          = $_GET['id'];
    $sql         = "SELECT * FROM ramais_sip WHERE id = :id";
    $query       = $con->prepare($sql);
    $query->bindParam(':id', $id);

    $query->execute();
    $registro = $query->fetch();

    // var_dump($registro); exit;

    require_once '../template/cabecalho.php';
    require_once 'form_ramais.php';
    require_once '../template/rodape.php';
}

/**
 * Ação Atualizar
 **/
else if ($acao == "atualizar") {
    $sql = "UPDATE ramais_sip
                  SET name = :name,
                      username = :username,
                      secret = :secret,
                      context = :context,
                      id_grupo = :id_grupo
                  WHERE id = :id";

    $query = $con->prepare($sql);

    $query->bindParam(':id', $_GET['id']);
    $query->bindParam(':name', $_POST['name']);
    $query->bindParam(':username', $_POST['username']);
    $query->bindParam(':secret', $_POST['secret']);
    $query->bindParam(':context', $_POST['context']);
    $query->bindParam(':id_grupo', $_POST['id_grupo']);
    $result = $query->execute();
    if ($result) {
        header('Location: ./ramais.php');
    } else {
        echo "Erro ao tentar atualizar os dados" . print_r($query->errorInfo());
    }
}


//função que retorna a lista de grupos cadastrados no banco
function getGrupos()
{
    $sql         = "SELECT * FROM grupo";
    $query       = $GLOBALS['con']->query($sql);
    $lista_grupo = $query->fetchAll();
    return $lista_grupo;
}


?>