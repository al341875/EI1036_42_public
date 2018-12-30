<?php  
include("./gestionBD.php");
$result = $pdo->prepare("SELECT * FROM A_GrupoCliente");
$result->execute();
$datos = $result->fetchAll(PDO::FETCH_ASSOC);
echo '$datos';
?>