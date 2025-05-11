<?php
session_start();
require_once 'functions.php';

// Déconnecter l'utilisateur
logoutUser();

// Rediriger vers la page d'accueil
header('Location: index.php');
exit();
?>
