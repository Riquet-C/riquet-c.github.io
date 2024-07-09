<?php
try {
    $mysql = new PDO('mysql:host=localhost;dbname=dump;charset=utf8', 'yes', 'yes');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
