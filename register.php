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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validation simple
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif ($password !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        if (registerUser($username, $email, $password, $conn)) {
            $success = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";
        } else {
            $error = "Cette adresse email est déjà utilisée.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - HostWebsite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-white">
  
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-gray-800 p-8 rounded-lg shadow-lg">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold">Inscription</h1>
                <p class="text-gray-400 mt-2">Créez votre compte HostWebsite</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-900 border border-red-700 text-white px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="bg-green-900 border border-green-700 text-white px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $success; ?></span>
                    <div class="mt-2">
                        <a href="login.php" class="text-green-300 font-semibold hover:underline">Se connecter</a>
                    </div>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-1">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Mot de passe</label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="text-sm text-gray-500 mt-1">Le mot de passe doit contenir au moins 8 caractères.</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-1">Confirmer le mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="terms" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 bg-gray-700 rounded" required>
                            <span class="ml-2 text-sm text-gray-300">J'accepte les <a href="terms.php" class="text-blue-400 hover:underline">conditions d'utilisation</a> et la <a href="privacy.php" class="text-blue-400 hover:underline">politique de confidentialité</a>.</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300">
                        S'inscrire
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        Vous avez déjà un compte? <a href="login.php" class="text-blue-400 hover:underline">Se connecter</a>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

 

    <script src="assets/js/main.js"></script>
</body>
</html>
