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

// Récupérer les abonnements de l'utilisateur
$subscriptions = getUserSubscriptions($userId, $conn);

// Compter les abonnements actifs
$activeSubscriptions = array_filter($subscriptions, function($sub) {
    return $sub['status'] === 'active';
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - HostWebsite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-white">
    <header class="bg-gray-800 py-4">
        <div class="container mx-auto px-4 flex flex-wrap items-center justify-between">
            <a href="index.php" class="text-2xl font-bold">LaeHost</a>
            
            <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            
            <nav id="menu" class="hidden md:flex w-full md:w-auto mt-4 md:mt-0">
                <ul class="flex flex-col md:flex-row md:space-x-6">
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
            </nav>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('hidden');
        });
    </script>

    <div class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold mb-8">Mon Compte</h1>
        
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="md:w-1/4">
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold"><?php echo htmlspecialchars($user['username']); ?></h2>
                            <p class="text-gray-400"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                    <nav>
                        <ul class="space-y-2">
                            <li>
                                <a href="account.php" class="block py-2 px-4 rounded bg-blue-600 hover:bg-blue-700 transition duration-300">Tableau de bord</a>
                            </li>
                            <li>
                                <a href="subscriptions.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition duration-300">Mes abonnements</a>
                            </li>
                            <li>
                                <a href="settings.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition duration-300">Paramètres du compte</a>
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
                <!-- Account Summary -->
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Résumé du compte</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="text-gray-400">Email</div>
                            <div><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="text-gray-400">Abonnements actifs</div>
                            <div><?php echo count($activeSubscriptions); ?></div>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="text-gray-400">Date d'inscription</div>
                            <div><?php echo formatDate($user['created_at']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Subscriptions -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Mes abonnements récents</h2>
                        <a href="subscriptions.php" class="text-blue-400 hover:underline">Voir tous</a>
                    </div>
                    
                    <?php if (empty($subscriptions)): ?>
                        <p class="text-gray-400">Vous n'avez aucun abonnement actif.</p>
                        <div class="mt-4">
                            <a href="services.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-300">Découvrir nos services</a>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-gray-700 rounded-lg">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 text-left">Service</th>
                                        <th class="py-3 px-4 text-left">Plan</th>
                                        <th class="py-3 px-4 text-left">Statut</th>
                                        <th class="py-3 px-4 text-left">Expiration</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $count = 0;
                                    foreach ($subscriptions as $sub): 
                                        if ($count >= 3) break; // Afficher seulement les 3 premiers
                                        $count++;
                                    ?>
                                        <tr class="border-t border-gray-600">
                                            <td class="py-3 px-4">
                                                <div class="flex items-center">
                                                    <span class="mr-2"><?php echo getServiceIcon($sub['service_type']); ?></span>
                                                    <?php echo getServiceName($sub['service_type']); ?>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4"><?php echo htmlspecialchars($sub['plan']); ?></td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 rounded-full text-xs <?php echo $sub['status'] === 'active' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'; ?>">
                                                    <?php echo $sub['status'] === 'active' ? 'Actif' : 'Expiré'; ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4"><?php echo formatDate($sub['end_date']); ?></td>
                                            <td class="py-3 px-4">
                                                <a href="subscription_details.php?id=<?php echo $sub['id']; ?>" class="text-blue-400 hover:underline">Détails</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Actions rapides</h2>
                        <div class="space-y-3">
                            <a href="new_subscription.php" class="block py-2 px-4 bg-blue-600 hover:bg-blue-700 rounded text-center transition duration-300">Nouvel abonnement</a>
                            <a href="support.php" class="block py-2 px-4 bg-gray-700 hover:bg-gray-600 rounded text-center transition duration-300">Contacter le support</a>
                            <a href="settings.php" class="block py-2 px-4 bg-gray-700 hover:bg-gray-600 rounded text-center transition duration-300">Modifier mon profil</a>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Ressources</h2>
                        <ul class="space-y-2 text-gray-300">
                            <li>
                                <a href="faq.php" class="flex items-center hover:text-blue-400 transition duration-300">
                                    <span class="mr-2">📚</span> FAQ et guides d'utilisation
                                </a>
                            </li>
                            <li>
                                <a href="tutorials.php" class="flex items-center hover:text-blue-400 transition duration-300">
                                    <span class="mr-2">🎓</span> Tutoriels d'installation
                                </a>
                            </li>
                            <li>
                                <a href="status.php" class="flex items-center hover:text-blue-400 transition duration-300">
                                    <span class="mr-2">📊</span> Statut des services
                                </a>
                            </li>
                            <li>
                                <a href="blog.php" class="flex items-center hover:text-blue-400 transition duration-300">
                                    <span class="mr-2">📰</span> Blog et actualités
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 py-12 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">HostWebsite</h3>
                    <p class="text-gray-400">Solutions d'hébergement de jeux professionnelles pour tous vos besoins.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Services</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="services.php#fivem" class="hover:text-white transition duration-300">FiveM</a></li>
                        <li><a href="services.php#minecraft" class="hover:text-white transition duration-300">Minecraft</a></li>
                        <li><a href="services.php#gmod" class="hover:text-white transition duration-300">Garry's Mod</a></li>
                        <li><a href="services.php#vps" class="hover:text-white transition duration-300">VPS Linux/Windows</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Liens utiles</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="about.php" class="hover:text-white transition duration-300">À propos</a></li>
                        <li><a href="contact.php" class="hover:text-white transition duration-300">Contact</a></li>
                        <li><a href="faq.php" class="hover:text-white transition duration-300">FAQ</a></li>
                        <li><a href="terms.php" class="hover:text-white transition duration-300">Conditions d'utilisation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>support@hostwebsite.com</li>
                        <li>+33 1 23 45 67 89</li>
                        <li>
                            <div class="flex space-x-4 mt-4">
                                <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> HostWebsite. Tous droits réservés.</p>
            </div>
        </div>
    </footer>


    <script src="assets/js/main.js"></script>
</body>
</html>
