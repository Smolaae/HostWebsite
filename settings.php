<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur
$userId = $_SESSION['user_id'];
$user = getUserInfo($userId, $conn);

$message = '';
$error = '';

// Traitement du formulaire de mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        
        // Validation simple
        if (empty($username) || empty($email)) {
            $error = "Le nom d'utilisateur et l'email sont obligatoires.";
        } else {
            // Vérifier si l'email est déjà utilisé par un autre utilisateur
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Cet email est déjà utilisé par un autre compte.";
            } else {
                // Mettre à jour le profil
                $data = [
                    'username' => $username,
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $phone,
                    'address' => $address
                ];
                
                if (updateUserInfo($userId, $data, $conn)) {
                    $message = "Votre profil a été mis à jour avec succès.";
                    
                    // Mettre à jour les données de l'utilisateur
                    $user = getUserInfo($userId, $conn);
                } else {
                    $error = "Une erreur est survenue lors de la mise à jour du profil.";
                }
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Vérifier que le mot de passe actuel est correct
        if (!password_verify($currentPassword, $user['password'])) {
            $error = "Le mot de passe actuel est incorrect.";
        } elseif (strlen($newPassword) < 8) {
            $error = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        } else {
            // Mettre à jour le mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $data = ['password' => $hashedPassword];
            
            if (updateUserInfo($userId, $data, $conn)) {
                $message = "Votre mot de passe a été changé avec succès.";
            } else {
                $error = "Une erreur est survenue lors du changement de mot de passe.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres du Compte - HostWebsite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-white">
  
    <div class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold mb-8">Paramètres du Compte</h1>
        
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="md:w-1/4">
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <nav>
                        <ul class="space-y-2">
                            <li>
                                <a href="account.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition duration-300">Tableau de bord</a>
                            </li>
                            <li>
                                <a href="subscriptions.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition duration-300">Mes abonnements</a>
                            </li>
                            <li>
                                <a href="settings.php" class="block py-2 px-4 rounded bg-blue-600 hover:bg-blue-700 transition duration-300">Paramètres du compte</a>
                            </li>
                            <li>
                                <a href="support.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition duration-300">Support</a>
                            </li>
                            <li>
                                <a href="logout.php" class="block py-2 px-4 rounded hover:bg-red-700 text-red-400 hover:text-white transition duration-300">Déconnexion</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="md:w-3/4">
                <?php if (!empty($message)): ?>
                    <div class="bg-green-900 border border-green-700 text-white px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $message; ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="bg-red-900 border border-red-700 text-white px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations du profil -->
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Informations du profil</h2>
                        <form method="POST" action="">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-300 mb-1">Nom d'utilisateur</label>
                                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-300 mb-1">Prénom</label>
                                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-300 mb-1">Nom</label>
                                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Téléphone</label>
                                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-300 mb-1">Adresse</label>
                                <textarea id="address" name="address" rows="3" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" name="update_profile" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-300">
                                Mettre à jour le profil
                            </button>
                        </form>
                    </div>
                    
                    <!-- Changer le mot de passe -->
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Changer le mot de passe</h2>
                        <form method="POST" action="">
                            <div class="mb-4">
                                <label for="current_password" class="block text-sm font-medium text-gray-300 mb-1">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="new_password" class="block text-sm font-medium text-gray-300 mb-1">Nouveau mot de passe</label>
                                <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-sm text-gray-500 mt-1">Le mot de passe doit contenir au moins 8 caractères.</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-1">Confirmer le nouveau mot de passe</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <button type="submit" name="change_password" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-300">
                                Changer le mot de passe
                            </button>
                        </form>
                    </div>
                    
                    <!-- Préférences de notification -->
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Préférences de notification</h2>
                        <form method="POST" action="">
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_notifications" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" <?php echo isset($user['email_notifications']) && $user['email_notifications'] ? 'checked' : ''; ?>>
                                    <span class="ml-2 text-sm text-gray-300">Recevoir des notifications par email</span>
                                </label>
                            </div>
                            
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="sms_notifications" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" <?php echo isset($user['sms_notifications']) && $user['sms_notifications'] ? 'checked' : ''; ?>>
                                    <span class="ml-2 text-sm text-gray-300">Recevoir des notifications par SMS</span>
                                </label>
                            </div>
                            
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="marketing_emails" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" <?php echo isset($user['marketing_emails']) && $user['marketing_emails'] ? 'checked' : ''; ?>>
                                    <span class="ml-2 text-sm text-gray-300">Recevoir des offres promotionnelles</span>
                                </label>
                            </div>
                            
                            <button type="submit" name="update_notifications" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-300">
                                Enregistrer les préférences
                            </button>
                        </form>
                    </div>
                    
                    <!-- Sécurité du compte -->
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Sécurité du compte</h2>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="two_factor" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" <?php echo isset($user['two_factor']) && $user['two_factor'] ? 'checked' : ''; ?>>
                                <span class="ml-2 text-sm text-gray-300">Activer l'authentification à deux facteurs</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-1 ml-6">Ajoute une couche de sécurité supplémentaire à votre compte.</p>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-300 mb-2">Sessions actives</h3>
                            <div class="bg-gray-700 p-3 rounded-md">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-sm font-medium">Session actuelle</div>
                                        <div class="text-xs text-gray-400">Dernière activité: <?php echo date('d/m/Y H:i'); ?></div>
                                    </div>
                                    <span class="px-2 py-1 bg-green-900 text-green-300 text-xs rounded-full">Actif</span>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded transition duration-300">
                            Déconnecter toutes les autres sessions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
