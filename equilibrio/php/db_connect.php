<?php
$db_host = '127.0.0.1'; 
$db_nome = 'equilibriomental';
$db_user = 'root'; 
$db_pass = '';


$dsn = "mysql:host=$db_host;dbname=$db_nome;charset=utf8mb4";

try {
  
    $pdo = new PDO($dsn, $db_user, $db_pass);

    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>