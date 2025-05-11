<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Récupérer le type de service depuis l'URL (optionnel)
$serviceType = isset($_GET['type']) ? $_GET['type'] : '';

// Liste des types de services disponibles
$availableServices = [
    'fivem' => [
        'name' => 'FiveM',
        'icon' => '🎮',
        'description' => 'Hébergez votre serveur FiveM avec des performances optimales et une protection anti-DDoS.',
        'plans' => [
            'starter' => [
                'name' => 'Starter',
                'slots' => 32,
                'ram' => '2GB',
                'price' => 14.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique']
            ],
            'standard' => [
                'name' => 'Standard',
                'slots' => 64,
                'ram' => '4GB',
                'price' => 24.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique', 'Sauvegarde quotidienne', 'Support prioritaire']
            ],
            'premium' => [
                'name' => 'Premium',
                'slots' => 128,
                'ram' => '8GB',
                'price' => 39.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique', 'Sauvegarde quotidienne', 'Support prioritaire', 'SSD NVMe', 'Performances optimisées']
            ]
        ]
    ],
    'minecraft' => [
        'name' => 'Minecraft',
        'icon' => '⛏️',
        'description' => 'Des serveurs Minecraft puissants avec installation automatique des mods et plugins.',
        'plans' => [
            'starter' => [
                'name' => 'Starter',
                'slots' => 10,
                'ram' => '2GB',
                'price' => 9.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique']
            ],
            'standard' => [
                'name' => 'Standard',
                'slots' => 30,
                'ram' => '4GB',
                'price' => 19.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique', 'Sauvegarde quotidienne', 'Support plugins']
            ],
            'premium' => [
                'name' => 'Premium',
                'slots' => 100,
                'ram' => '8GB',
                'price' => 34.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique', 'Sauvegarde quotidienne', 'Support plugins', 'SSD NVMe', 'Assistance mods']
            ]
        ]
    ],
    'gmod' => [
        'name' => 'Garry\'s Mod',
        'icon' => '🔧',
        'description' => 'Hébergement Garry\'s Mod avec installation automatique des addons et workshop.',
        'plans' => [
            'starter' => [
                'name' => 'Starter',
                'slots' => 16,
                'ram' => '2GB',
                'price' => 12.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique']
            ],
            'standard' => [
                'name' => 'Standard',
                'slots' => 32,
                'ram' => '4GB',
                'price' => 22.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique', 'Sauvegarde quotidienne', 'Support workshop']
            ],
            'premium' => [
                'name' => 'Premium',
                'slots' => 64,
                'ram' => '8GB',
                'price' => 32.99,
                'features' => ['Protection DDoS', 'Panneau de contrôle', 'Installation automatique', 'Sauvegarde quotidienne', 'Support workshop', 'SSD NVMe', 'Assistance addons']
            ]
        ]
    ],
    'linux' => [
        'name' => 'VPS Linux',
        'icon' => '🐧',
        'description' => 'Serveurs VPS Linux performants pour une flexibilité maximale.',
        'plans' => [
            'starter' => [
                'name' => 'Starter',
                'cpu' => '1 vCPU',
                'ram' => '2GB',
                'storage' => '20GB SSD',
                'price' => 9.99,
                'features' => ['Protection DDoS', 'Accès Root', 'Choix de distribution']
            ],
            'standard' => [
                'name' => 'Standard',
                'cpu' => '2 vCPU',
                'ram' => '4GB',
                'storage' => '50GB SSD',
                'price' => 19.99,
                'features' => ['Protection DDoS', 'Accès Root', 'Choix de distribution', 'Sauvegarde hebdomadaire', 'Trafic illimité']
            ],
            'premium' => [
                'name' => 'Premium',
                'cpu' => '4 vCPU',
                'ram' => '8GB',
                'storage' => '100GB SSD',
                'price' => 34.99,
                'features' => ['Protection DDoS', 'Accès Root', 'Choix de distribution', 'Sauvegarde quotidienne', 'Trafic illimité', 'SSD NVMe', 'Support prioritaire']
            ]
        ]
    ],
    'windows' => [
        'name' => 'VPS Windows',
        'icon' => '🪟',
        'description' => 'Serveurs VPS Windows avec interface de gestion intuitive.',
        'plans' => [
            'starter' => [
                'name' => 'Starter',
                'cpu' => '1 vCPU',
                'ram' => '2GB',
                'storage' => '40GB SSD',
                'price' => 14.99,
                'features' => ['Protection DDoS', 'Accès Admin', 'Windows Server 2019']
            ],
            'standard' => [
                'name' => 'Standard',
                'cpu' => '2 vCPU',
                'ram' => '4GB',
                'storage' => '80GB SSD',
                'price' => 24.99,
                'features' => ['Protection DDoS', 'Accès Admin', 'Windows Server 2019', 'Sauvegarde hebdomadaire', 'Trafic illimité']
            ],
            'premium' => [
                'name' => 'Premium',
                'cpu' => '4 vCPU',
                'ram' => '8GB',
                'storage' => '160GB SSD',
                'price' => 44.99,
                'features' => ['Protection DDoS', 'Accès Admin', 'Windows Server 2019', 'Sauvegarde quotidienne', 'Trafic illimité', 'SSD NVMe', 'Support prioritaire']
            ]
        ]
    ]
];

$success = '';
$error = '';

// Traitement du formulaire de souscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedType = isset($_POST['service_type']) ? $_POST['service_type'] : '';
    $selectedPlan = isset($_POST['plan']) ? $_POST['plan'] : '';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : '';
    $paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
    
    if (empty($selectedType) || empty($selectedPlan) || empty($duration) || empty($paymentMethod)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!array_key_exists($selectedType, $availableServices) || !array_key_exists($selectedPlan, $availableServices[$selectedType]['plans'])) {
        $error = "Service ou plan invalide.";
    } else {
        // Calculer la durée en mois
        $months = 0;
        switch ($duration) {
            case '1month':
                $months = 1;
                break;
            case '3months':
                $months = 3;
                break;
            case '6months':
                $months = 6;
                break;
            case '12months':
                $months = 12;
                break;
            default:
                $months = 1;
        }
        
        // Récupérer les informations du plan
        $plan = $availableServices[$selectedType]['plans'][$selectedPlan];
        $planName = $plan['name'];
        $price = $plan['price'];
        
        // Appliquer des réductions pour les abonnements plus longs
        $totalPrice = $price * $months;
        if ($months == 3) {
            $totalPrice *= 0.95; // 5% de réduction
        } elseif ($months == 6) {
            $totalPrice *= 0.90; // 10% de réduction
        } elseif ($months == 12) {
            $totalPrice *= 0.85; // 15% de réduction
        }
        
        // Dans un environnement réel, ici on traiterait le paiement
        
        // Calculer les dates de début et de fin
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $months . ' months'));
        
        // Générer une IP et un port fictifs pour le serveur
        $serverIp = '192.168.' . rand(1, 254) . '.' . rand(1, 254);
        $serverPort = ($selectedType === 'fivem') ? 30120 : (($selectedType === 'minecraft') ? 25565 : (($selectedType === 'gmod') ? 27015 : 22));
        
        // Créer les détails du serveur
        $serverDetails = "Plan: " . $planName . "\n";
        if (isset($plan['slots'])) {
            $serverDetails .= "Slots: " . $plan['slots'] . "\n";
        }
        if (isset($plan['cpu'])) {
            $serverDetails .= "CPU: " . $plan['cpu'] . "\n";
        }
        $serverDetails .= "RAM: " . $plan['ram'] . "\n";
        if (isset($plan['storage'])) {
            $serverDetails .= "Stockage: " . $plan['storage'] . "\n";
        }
        
        // Insérer le nouvel abonnement dans la base de données
        $stmt = $conn->prepare("INSERT INTO subscriptions (user_id, service_type, plan, price, start_date, end_date, status, server_ip, server_port, server_details, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', ?, ?, ?, NOW())");
        $stmt->bind_param("sssssssis", 
            $userId,          // i : int
            $selectedType,    // s : string
            $planName,        // s : string
            $price,           // s : float (ou string si besoin)
            $startDate,       // s : string (date)
            $endDate,         // s : string (date)
            $serverIp,        // s : string (erreur : tu avais "i")
            $serverPort,      // i : int
            $serverDetails    // s : string
        );

        if ($stmt->execute()) {
            $subscriptionId = $conn->insert_id;
            
            // Enregistrer le paiement
            $transactionId = 'TRX' . time() . rand(1000, 9999);
            $stmt = $conn->prepare("INSERT INTO payments (user_id, subscription_id, amount, payment_date, payment_method, transaction_id, status) VALUES (?, ?, ?, NOW(), ?, ?, 'completed')");
            $stmt->bind_param("iidss", $userId, $subscriptionId, $totalPrice, $paymentMethod, $transactionId);
            $stmt->execute();
            
            $success = "Votre abonnement a été créé avec succès ! Vous pouvez maintenant accéder à votre serveur.";
            
            // Rediriger vers la page de détails de l'abonnement après 3 secondes
            header("refresh:3;url=subscription_details.php?id=" . $subscriptionId);
        } else {
            $error = "Une erreur est survenue lors de la création de l'abonnement.";
        }
    }
}

// Définir l'étape du processus
$step = 1;
if (!empty($serviceType) && array_key_exists($serviceType, $availableServices)) {
    $step = 2;
    if (isset($_GET['plan']) && array_key_exists($_GET['plan'], $availableServices[$serviceType]['plans'])) {
        $step = 3;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel abonnement - HostWebsite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-white">


    <div class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold mb-8">Nouvel abonnement</h1>
        
        <?php if (!empty($success)): ?>
            <div class="bg-green-900 border border-green-700 text-white px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $success; ?></span>
                <p class="mt-2">Vous allez être redirigé vers les détails de votre abonnement...</p>
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="bg-red-900 border border-red-700 text-white px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (empty($success)): ?>
            <!-- Étapes du processus -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="flex items-center justify-center">
                            <div class="<?php echo $step >= 1 ? 'bg-blue-600' : 'bg-gray-700'; ?> rounded-full h-10 w-10 flex items-center justify-center font-bold text-white">1</div>
                        </div>
                        <div class="text-center mt-2 <?php echo $step >= 1 ? 'text-blue-400' : 'text-gray-500'; ?>">Choisir un service</div>
                    </div>
                    <div class="w-full max-w-[100px] h-1 <?php echo $step >= 2 ? 'bg-blue-600' : 'bg-gray-700'; ?>"></div>
                    <div class="flex-1">
                        <div class="flex items-center justify-center">
                            <div class="<?php echo $step >= 2 ? 'bg-blue-600' : 'bg-gray-700'; ?> rounded-full h-10 w-10 flex items-center justify-center font-bold text-white">2</div>
                        </div>
                        <div class="text-center mt-2 <?php echo $step >= 2 ? 'text-blue-400' : 'text-gray-500'; ?>">Sélectionner un plan</div>
                    </div>
                    <div class="w-full max-w-[100px] h-1 <?php echo $step >= 3 ? 'bg-blue-600' : 'bg-gray-700'; ?>"></div>
                    <div class="flex-1">
                        <div class="flex items-center justify-center">
                            <div class="<?php echo $step >= 3 ? 'bg-blue-600' : 'bg-gray-700'; ?> rounded-full h-10 w-10 flex items-center justify-center font-bold text-white">3</div>
                        </div>
                        <div class="text-center mt-2 <?php echo $step >= 3 ? 'text-blue-400' : 'text-gray-500'; ?>">Finaliser la commande</div>
                    </div>
                </div>
            </div>
            
            <?php if ($step == 1): ?>
                <!-- Étape 1: Choisir un service -->
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-6">Choisissez un type de service</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($availableServices as $type => $service): ?>
                            <a href="new_subscription.php?type=<?php echo $type; ?>" class="block">
                                <div class="bg-gray-700 rounded-lg p-6 hover:bg-gray-600 transition duration-300 h-full flex flex-col">
                                    <div class="flex items-center mb-4">
                                        <div class="text-4xl mr-4"><?php echo $service['icon']; ?></div>
                                        <h3 class="text-xl font-bold"><?php echo $service['name']; ?></h3>
                                    </div>
                                    <p class="text-gray-300 mb-4 flex-grow"><?php echo $service['description']; ?></p>
                                    <div class="text-blue-400 flex items-center mt-2">
                                        <span>Voir les plans</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Besoin d'aide pour choisir ?</h2>
                    <p class="text-gray-300 mb-4">
                        Nous proposons différents types de services adaptés à vos besoins. Si vous avez des questions ou si vous n'êtes pas sûr du service qui vous convient le mieux, n'hésitez pas à contacter notre équipe de support.
                    </p>
                    <a href="contact.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-300">
                        Nous contacter
                    </a>
                </div>
                
            <?php elseif ($step == 2): ?>
                <!-- Étape 2: Sélectionner un plan -->
                <div class="mb-4">
                    <a href="new_subscription.php" class="text-blue-400 hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Retour à la sélection du service
                    </a>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center mb-6">
                        <div class="text-4xl mr-4"><?php echo $availableServices[$serviceType]['icon']; ?></div>
                        <div>
                            <h2 class="text-2xl font-bold"><?php echo $availableServices[$serviceType]['name']; ?></h2>
                            <p class="text-gray-400"><?php echo $availableServices[$serviceType]['description']; ?></p>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold mb-4">Choisissez votre plan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                            <div class="bg-gray-700 rounded-lg p-6 border-2 <?php echo ($planId === 'standard') ? 'border-blue-500' : 'border-transparent'; ?> relative">
                                <?php if ($planId === 'standard'): ?>
                                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        Recommandé
                                    </div>
                                <?php endif; ?>
                                
                                <h4 class="text-lg font-bold mb-2"><?php echo $plan['name']; ?></h4>
                                <div class="text-2xl font-bold mb-4"><?php echo number_format($plan['price'], 2); ?> €<span class="text-sm text-gray-400">/mois</span></div>
                                
                                <div class="mb-4">
                                    <?php if (isset($plan['slots'])): ?>
                                        <div class="flex justify-between py-2 border-b border-gray-600">
                                            <span>Slots</span>
                                            <span class="font-medium"><?php echo $plan['slots']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($plan['cpu'])): ?>
                                        <div class="flex justify-between py-2 border-b border-gray-600">
                                            <span>CPU</span>
                                            <span class="font-medium"><?php echo $plan['cpu']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="flex justify-between py-2 border-b border-gray-600">
                                        <span>RAM</span>
                                        <span class="font-medium"><?php echo $plan['ram']; ?></span>
                                    </div>
                                    
                                    <?php if (isset($plan['storage'])): ?>
                                        <div class="flex justify-between py-2 border-b border-gray-600">
                                            <span>Stockage</span>
                                            <span class="font-medium"><?php echo $plan['storage']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <ul class="mb-6 space-y-2">
                                    <?php foreach ($plan['features'] as $feature): ?>
                                        <li class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm"><?php echo $feature; ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                
                                <a href="new_subscription.php?type=<?php echo $serviceType; ?>&plan=<?php echo $planId; ?>" class="block w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded text-center transition duration-300">
                                    Sélectionner
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Comparer les plans</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-700 rounded-lg">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 text-left">Caractéristique</th>
                                    <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                        <th class="py-3 px-4 text-left"><?php echo $plan['name']; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-t border-gray-600">
                                    <td class="py-3 px-4">Prix mensuel</td>
                                    <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                        <td class="py-3 px-4"><?php echo number_format($plan['price'], 2); ?> €</td>
                                    <?php endforeach; ?>
                                </tr>
                                
                                <?php if (isset(reset($availableServices[$serviceType]['plans'])['slots'])): ?>
                                    <tr class="border-t border-gray-600">
                                        <td class="py-3 px-4">Slots</td>
                                        <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                            <td class="py-3 px-4"><?php echo $plan['slots']; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if (isset(reset($availableServices[$serviceType]['plans'])['cpu'])): ?>
                                    <tr class="border-t border-gray-600">
                                        <td class="py-3 px-4">CPU</td>
                                        <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                            <td class="py-3 px-4"><?php echo $plan['cpu']; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endif; ?>
                                
                                <tr class="border-t border-gray-600">
                                    <td class="py-3 px-4">RAM</td>
                                    <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                        <td class="py-3 px-4"><?php echo $plan['ram']; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                
                                <?php if (isset(reset($availableServices[$serviceType]['plans'])['storage'])): ?>
                                    <tr class="border-t border-gray-600">
                                        <td class="py-3 px-4">Stockage</td>
                                        <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                            <td class="py-3 px-4"><?php echo $plan['storage']; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endif; ?>
                                
                                <tr class="border-t border-gray-600">
                                    <td class="py-3 px-4">Protection DDoS</td>
                                    <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                        <td class="py-3 px-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                                
                                <tr class="border-t border-gray-600">
                                    <td class="py-3 px-4">Sauvegarde automatique</td>
                                    <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                        <td class="py-3 px-4">
                                            <?php if ($planId === 'starter'): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            <?php elseif ($planId === 'standard'): ?>
                                                Hebdomadaire
                                            <?php else: ?>
                                                Quotidienne
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                                
                                <tr class="border-t border-gray-600">
                                    <td class="py-3 px-4">Support prioritaire</td>
                                    <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                        <td class="py-3 px-4">
                                            <?php if ($planId === 'starter' || $planId === 'standard'): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                                
                                <tr class="border-t border-gray-600">
                                    <td class="py-3 px-4">SSD NVMe</td>
                                    <?php foreach ($availableServices[$serviceType]['plans'] as $planId => $plan): ?>
                                        <td class="py-3 px-4">
                                            <?php if ($planId === 'starter' || $planId === 'standard'): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            <?php elseif ($step == 3): ?>
                <!-- Étape 3: Finaliser la commande -->
                <div class="mb-4">
                    <a href="new_subscription.php?type=<?php echo $serviceType; ?>" class="text-blue-400 hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Retour à la sélection du plan
                    </a>
                </div>
                
                <?php
                $selectedPlan = $_GET['plan'];
                $plan = $availableServices[$serviceType]['plans'][$selectedPlan];
                ?>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Informations sur la commande -->
                    <div class="md:col-span-2">
                        <div class="bg-gray-800 rounded-lg p-6 mb-6">
                            <h2 class="text-xl font-bold mb-4">Votre commande</h2>
                            
                            <div class="flex items-center mb-6">
                                <div class="text-4xl mr-4"><?php echo $availableServices[$serviceType]['icon']; ?></div>
                                <div>
                                    <h3 class="text-xl font-bold"><?php echo $availableServices[$serviceType]['name']; ?></h3>
                                    <p class="text-gray-400">Plan <?php echo $plan['name']; ?></p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-700 rounded-lg p-4 mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php if (isset($plan['slots'])): ?>
                                        <div>
                                            <div class="text-gray-400 text-sm">Slots</div>
                                            <div class="font-medium"><?php echo $plan['slots']; ?></div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($plan['cpu'])): ?>
                                        <div>
                                            <div class="text-gray-400 text-sm">CPU</div>
                                            <div class="font-medium"><?php echo $plan['cpu']; ?></div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <div class="text-gray-400 text-sm">RAM</div>
                                        <div class="font-medium"><?php echo $plan['ram']; ?></div>
                                    </div>
                                    
                                    <?php if (isset($plan['storage'])): ?>
                                        <div>
                                            <div class="text-gray-400 text-sm">Stockage</div>
                                            <div class="font-medium"><?php echo $plan['storage']; ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-bold mb-2">Caractéristiques incluses</h3>
                            <ul class="mb-6 space-y-2 pl-6">
                                <?php foreach ($plan['features'] as $feature): ?>
                                    <li class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span><?php echo $feature; ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <!-- Formulaire de souscription -->
                        <div class="bg-gray-800 rounded-lg p-6">
                            <h2 class="text-xl font-bold mb-4">Finaliser votre abonnement</h2>
                            
                            <form method="POST" action="" class="space-y-6">
                                <input type="hidden" name="service_type" value="<?php echo $serviceType; ?>">
                                <input type="hidden" name="plan" value="<?php echo $selectedPlan; ?>">
                                
                                <div>
                                    <label class="text-gray-300 mb-2 block">Sélectionnez une durée</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="relative flex items-start p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition duration-200">
                                            <input type="radio" name="duration" value="1month" class="mt-1 mr-2" checked>
                                            <div>
                                                <span class="block font-medium">1 mois</span>
                                                <span class="text-gray-400 text-sm">
                                                    <?php echo number_format($plan['price'], 2); ?> €/mois
                                                </span>
                                                <span class="block text-gray-300 mt-1 text-lg font-bold">
                                                    <?php echo number_format($plan['price'], 2); ?> €
                                                </span>
                                            </div>
                                        </label>
                                        <label class="relative flex items-start p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition duration-200">
                                            <input type="radio" name="duration" value="3months" class="mt-1 mr-2">
                                            <div>
                                                <span class="block font-medium">3 mois</span>
                                                <span class="text-gray-400 text-sm">
                                                    <?php echo number_format($plan['price'] * 0.95, 2); ?> €/mois
                                                </span>
                                                <span class="block text-gray-300 mt-1 text-lg font-bold">
                                                    <?php echo number_format($plan['price'] * 3 * 0.95, 2); ?> €
                                                </span>
                                                <span class="text-green-400 text-sm">Économisez 5%</span>
                                            </div>
                                        </label>
                                        <label class="relative flex items-start p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition duration-200">
                                            <input type="radio" name="duration" value="6months" class="mt-1 mr-2">
                                            <div>
                                                <span class="block font-medium">6 mois</span>
                                                <span class="text-gray-400 text-sm">
                                                    <?php echo number_format($plan['price'] * 0.90, 2); ?> €/mois
                                                </span>
                                                <span class="block text-gray-300 mt-1 text-lg font-bold">
                                                    <?php echo number_format($plan['price'] * 6 * 0.90, 2); ?> €
                                                </span>
                                                <span class="text-green-400 text-sm">Économisez 10%</span>
                                            </div>
                                        </label>
                                        <label class="relative flex items-start p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition duration-200">
                                            <input type="radio" name="duration" value="12months" class="mt-1 mr-2">
                                            <div>
                                                <span class="block font-medium">1 an</span>
                                                <span class="text-gray-400 text-sm">
                                                    <?php echo number_format($plan['price'] * 0.85, 2); ?> €/mois
                                                </span>
                                                <span class="block text-gray-300 mt-1 text-lg font-bold">
                                                    <?php echo number_format($plan['price'] * 12 * 0.85, 2); ?> €
                                                </span>
                                                <span class="text-green-400 text-sm">Économisez 15%</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="text-gray-300 mb-2 block">Méthode de paiement</label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <label class="relative flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition duration-200">
                                            <input type="radio" name="payment_method" value="card" class="mr-2" checked>
                                            <span>Carte bancaire</span>
                                        </label>
                                        <label class="relative flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition duration-200">
                                            <input type="radio" name="payment_method" value="paypal" class="mr-2">
                                            <span>PayPal</span>
                                        </label>
                                        <label class="relative flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition duration-200">
                                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-2">
                                            <span>Virement bancaire</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition duration-300 font-bold">
                                        Confirmer la commande
                                    </button>
                                    <p class="text-center text-gray-400 mt-2 text-sm">
                                        En cliquant sur ce bouton, vous acceptez nos <a href="terms.php" class="text-blue-400 hover:underline">conditions d'utilisation</a>.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Résumé de la commande -->
                    <div>
                        <div class="bg-gray-800 rounded-lg p-6 mb-6 sticky top-6">
                            <h2 class="text-xl font-bold mb-4">Résumé de la commande</h2>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Service:</span>
                                    <span><?php echo $availableServices[$serviceType]['name']; ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Plan:</span>
                                    <span><?php echo $plan['name']; ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Prix mensuel:</span>
                                    <span><?php echo number_format($plan['price'], 2); ?> €</span>
                                </div>
                                
                                <div class="border-t border-gray-700 my-4 pt-4">
                                    <div class="flex justify-between font-bold">
                                        <span>Total (1 mois):</span>
                                        <span><?php echo number_format($plan['price'], 2); ?> €</span>
                                    </div>
                                    <p class="text-gray-400 text-sm mt-2">
                                        Le prix total s'ajustera en fonction de la durée sélectionnée.
                                    </p>
                                </div>
                                
                                <div class="border-t border-gray-700 pt-4 mt-4">
                                    <div class="bg-blue-900 p-4 rounded-lg mb-4">
                                        <h3 class="font-bold mb-2">Activation instantanée</h3>
                                        <p class="text-sm text-gray-300">
                                            Votre serveur sera activé instantanément après confirmation du paiement.
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm">Garantie de remboursement de 7 jours</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-6">
                            <h2 class="text-xl font-bold mb-4">Besoin d'aide ?</h2>
                            <p class="text-gray-400 mb-4">
                                Notre équipe de support est disponible 24/7 pour répondre à toutes vos questions.
                            </p>
                            <a href="contact.php" class="block py-2 px-4 bg-gray-700 hover:bg-gray-600 rounded text-center transition duration-300">
                                Contacter le support
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>


    <script src="assets/js/main.js"></script>
</body>
</html>
