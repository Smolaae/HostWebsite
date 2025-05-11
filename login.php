<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

// Rediriger si déjà connecté
if (isLoggedIn()) {
    header('Location: account.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        if (authenticateUser($email, $password, $conn)) {
            header('Location: account.php');
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - HostWebsite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-white">


    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-gray-800 p-8 rounded-lg shadow-lg">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold">Connexion</h1>
                <p class="text-gray-400 mt-2">Accédez à votre compte LaeHost</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-900 border border-red-700 text-white px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Mot de passe</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <div class="flex justify-end mt-1">
                        <a href="forgot_password.php" class="text-sm text-blue-400 hover:underline">Mot de passe oublié?</a>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300">
                    Se connecter
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    Vous n'avez pas de compte? <a href="register.php" class="text-blue-400 hover:underline">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>


    <script src="assets/js/main.js"></script>
</body>
</html>
