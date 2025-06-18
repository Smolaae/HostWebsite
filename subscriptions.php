<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Récupérer les abonnements de l'utilisateur
$userId = $_SESSION['user_id'];
$subscriptions = getUserSubscriptions($userId, $conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Abonnements - HostWebsite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8a2be2',
                        secondary: '#6a11cb',
                        dark: {
                            DEFAULT: '#121212',
                            light: '#1e1e1e',
                            lighter: '#2d2d2d'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
</head>
<body class="bg-gray-900 text-white">
   <header class="bg-gray-800 py-4">
        <div class="container flex flex-row justify-between mx-auto px-4 py-4">
            <div class="flex items-center">
                <i class="fas fa-server text-primary text-2xl mr-2"></i>
                <span class=" text-2xl font-bold">LaeHosting</span>
                <span class="ml-2 text-gray-400">/ Mes abonnements</span>
            </div>
            <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
                
                <div id="menu" class="hidden md:flex w-full md:w-auto mt-4 md:mt-0">
                    <ul class="flex md:flex-row md:space-x-6">
                        <li><a href="index.php" class="block py-2 hover:text-blue-400 transition duration-300">Accueil</a></li>
                        <li><a href="services.php" class="block py-2 hover:text-blue-400 transition duration-300">Services</a></li>                   
                        <li><a href="contact.php" class="block py-2 hover:text-blue-400 transition duration-300">Contact</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="account.php" class="block py-2 hover:text-blue-400 transition duration-300">Mon compte</a></li>
                            <li><a href="logout.php" class="block py-2 hover:text-blue-400 transition duration-300">Déconnexion</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="block py-2 hover:text-blue-400 transition duration-300">Connexion</a></li>
                            <li><a href="register.php" class="block py-2 hover:text-blue-400 transition duration-300">Inscription</a></li>
                        <?php endif; ?>
                    </ul>
                        </div>
            </div>
        </div>
    </header>
    <div class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold mb-8">Mes Abonnements</h1>
        
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
                                <a href="subscriptions.php" class="block py-2 px-4 rounded bg-blue-600 hover:bg-blue-700 transition duration-300">Mes abonnements</a>
                            </li>
                            <li>
                                <a href="settings.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition duration-300">Paramètres du compte</a>
                            </li>
                            <li>
                                <a href="ticket.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition duration-300">Support</a>
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
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Liste des abonnements</h2>
                        <a href="new_subscription.php" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-300">Nouvel abonnement</a>
                    </div>

                    <?php if (empty($subscriptions)): ?>
                        <div class="bg-gray-700 rounded-lg p-6 text-center">
                            <p class="text-gray-300 mb-4">Vous n'avez aucun abonnement actif.</p>
                            <a href="services.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-300">Découvrir nos services</a>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-gray-700 rounded-lg">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 text-left">Service</th>
                                        <th class="py-3 px-4 text-left">Plan</th>
                                        <th class="py-3 px-4 text-left">Date de début</th>
                                        <th class="py-3 px-4 text-left">Date d'expiration</th>
                                        <th class="py-3 px-4 text-left">Statut</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subscriptions as $sub): ?>
                                        <tr class="border-t border-gray-600">
                                            <td class="py-3 px-4">
                                                <div class="flex items-center">
                                                    <span class="mr-2"><?php echo getServiceIcon($sub['service_type']); ?></span>
                                                    <?php echo getServiceName($sub['service_type']); ?>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4"><?php echo htmlspecialchars($sub['plan']); ?></td>
                                            <td class="py-3 px-4"><?php echo formatDate($sub['start_date']); ?></td>
                                            <td class="py-3 px-4"><?php echo formatDate($sub['end_date']); ?></td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 rounded-full text-xs <?php echo $sub['status'] === 'active' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'; ?>">
                                                    <?php echo $sub['status'] === 'active' ? 'Actif' : 'Expiré'; ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex space-x-2">
                                                    <a href="subscription_details.php?id=<?php echo $sub['id']; ?>" class="text-blue-400 hover:underline">Détails</a>
                                                    <?php if ($sub['status'] === 'active'): ?>
                                                    <a href="renew_subscription.php?id=<?php echo $sub['id']; ?>" class="text-green-400 hover:underline">Renouveler</a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Services disponibles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border border-gray-700 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                            <div class="text-xl mb-2">🎮 FiveM</div>
                            <p class="text-gray-400 mb-4">Hébergez votre serveur FiveM avec des performances optimales.</p>
                            <a href="new_subscription.php?type=fivem" class="text-blue-400 hover:underline">Voir les plans</a>
                        </div>
                        <div class="border border-gray-700 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                            <div class="text-xl mb-2">⛏️ Minecraft</div>
                            <p class="text-gray-400 mb-4">Serveurs Minecraft avec protection anti-DDoS et support 24/7.</p>
                            <a href="new_subscription.php?type=minecraft" class="text-blue-400 hover:underline">Voir les plans</a>
                        </div>
                        <div class="border border-gray-700 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                            <div class="text-xl mb-2">🔧 GMod</div>
                            <p class="text-gray-400 mb-4">Hébergement Garry's Mod avec installation automatique des addons.</p>
                            <a href="new_subscription.php?type=gmod" class="text-blue-400 hover:underline">Voir les plans</a>
                        </div>
                        <div class="border border-gray-700 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                            <div class="text-xl mb-2">🐧 Linux</div>
                            <p class="text-gray-400 mb-4">Serveurs VPS Linux pour une flexibilité maximale.</p>
                            <a href="new_subscription.php?type=linux" class="text-blue-400 hover:underline">Voir les plans</a>
                        </div>
                        <div class="border border-gray-700 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                            <div class="text-xl mb-2">🪟 Windows</div>
                            <p class="text-gray-400 mb-4">Serveurs VPS Windows avec interface de gestion intuitive.</p>
                            <a href="new_subscription.php?type=windows" class="text-blue-400 hover:underline">Voir les plans</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
