<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Vérifier si l'ID de l'abonnement est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: subscriptions.php');
    exit();
}

$subscriptionId = $_GET['id'];
$userId = $_SESSION['user_id'];

// Récupérer les détails de l'abonnement
$subscription = getSubscription($subscriptionId, $userId, $conn);

// Vérifier si l'abonnement existe et appartient à l'utilisateur
if (!$subscription) {
    header('Location: subscriptions.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'abonnement - HostWebsite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-white">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto py-10 px-4">
        <div class="flex items-center mb-8">
            <a href="subscriptions.php" class="text-blue-400 hover:underline mr-4">
                &larr; Retour aux abonnements
            </a>
            <h1 class="text-3xl font-bold"><?php echo getServiceName($subscription['service_type']); ?> - <?php echo htmlspecialchars($subscription['plan']); ?></h1>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="md:col-span-2">
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="text-4xl mr-4"><?php echo getServiceIcon($subscription['service_type']); ?></div>
                            <div>
                                <h2 class="text-2xl font-bold"><?php echo getServiceName($subscription['service_type']); ?></h2>
                                <p class="text-gray-400"><?php echo htmlspecialchars($subscription['plan']); ?></p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm <?php echo $subscription['status'] === 'active' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'; ?>">
                            <?php echo $subscription['status'] === 'active' ? 'Actif' : 'Expiré'; ?>
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="text-gray-400 text-sm">Date de début</div>
                            <div><?php echo formatDate($subscription['start_date']); ?></div>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="text-gray-400 text-sm">Date d'expiration</div>
                            <div><?php echo formatDate($subscription['end_date']); ?></div>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="text-gray-400 text-sm">Prix mensuel</div>
                            <div><?php echo number_format($subscription['price'], 2); ?> €</div>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="text-gray-400 text-sm">Renouvellement automatique</div>
                            <div><?php echo isset($subscription['auto_renew']) && $subscription['auto_renew'] ? 'Activé' : 'Désactivé'; ?></div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">Informations de connexion</h3>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-gray-400 text-sm">Adresse IP</div>
                                    <div class="flex items-center">
                                        <span class="mr-2"><?php echo htmlspecialchars($subscription['server_ip']); ?></span>
                                        <button class="text-blue-400 hover:text-blue-300" onclick="copyToClipboard('<?php echo htmlspecialchars($subscription['server_ip']); ?>')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-gray-400 text-sm">Port</div>
                                    <div><?php echo htmlspecialchars($subscription['server_port']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($subscription['server_details'])): ?>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Détails du serveur</h3>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <pre class="text-sm whitespace-pre-wrap"><?php echo htmlspecialchars($subscription['server_details']); ?></pre>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Statistiques du serveur -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Statistiques du serveur</h2>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>CPU</span>
                                <span>45%</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>RAM</span>
                                <span>3.2 GB / 8 GB</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 40%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Stockage</span>
                                <span>25 GB / 50 GB</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 50%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Bande passante</span>
                                <span>120 GB / 500 GB</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 24%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div>
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Actions</h2>
                    <div class="space-y-3">
                        <?php if ($subscription['status'] === 'active'): ?>
                        <a href="server_control.php?id=<?php echo $subscription['id']; ?>" class="block py-2 px-4 bg-blue-600 hover:bg-blue-700 rounded text-center transition duration-300">
                            Panneau de contrôle
                        </a>
                        <a href="renew_subscription.php?id=<?php echo $subscription['id']; ?>" class="block py-2 px-4 bg-green-600 hover:bg-green-700 rounded text-center transition duration-300">
                            Renouveler l'abonnement
                        </a>
                        <a href="upgrade_plan.php?id=<?php echo $subscription['id']; ?>" class="block py-2 px-4 bg-purple-600 hover:bg-purple-700 rounded text-center transition duration-300">
                            Mettre à niveau
                        </a>
                        <?php else: ?>
                        <a href="reactivate_subscription.php?id=<?php echo $subscription['id']; ?>" class="block py-2 px-4 bg-green-600 hover:bg-green-700 rounded text-center transition duration-300">
                            Réactiver l'abonnement
                        </a>
                        <?php endif; ?>
                        <a href="support_ticket.php?subscription_id=<?php echo $subscription['id']; ?>" class="block py-2 px-4 bg-gray-700 hover:bg-gray-600 rounded text-center transition duration-300">
                            Contacter le support
                        </a>
                        <?php if ($subscription['status'] === 'active'): ?>
                        <button type="button" class="block w-full py-2 px-4 bg-red-600 hover:bg-red-700 rounded text-center transition duration-300" onclick="confirmCancel()">
                            Annuler l'abonnement
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Ressources</h2>
                    <ul class="space-y-2 text-gray-300">
                        <li>
                            <a href="#" class="flex items-center hover:text-blue-400 transition duration-300">
                                <span class="mr-2">📚</span> Guide d'utilisation
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center hover:text-blue-400 transition duration-300">
                                <span class="mr-2">🎓</span> Tutoriels d'installation
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center hover:text-blue-400 transition duration-300">
                                <span class="mr-2">🔧</span> Dépannage courant
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center hover:text-blue-400 transition duration-300">
                                <span class="mr-2">📝</span> Documentation API
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Copié dans le presse-papiers !');
            }, function(err) {
                console.error('Erreur lors de la copie: ', err);
            });
        }
        
        function confirmCancel() {
            if (confirm('Êtes-vous sûr de vouloir annuler cet abonnement ? Cette action ne peut pas être annulée.')) {
                window.location.href = 'cancel_subscription.php?id=<?php echo $subscription['id']; ?>';
            }
        }
    </script>
    <script src="assets/js/main.js"></script>
</body>
</html>
