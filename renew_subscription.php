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

$message = '';
$error = '';

// Traitement du formulaire de renouvellement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duration = isset($_POST['duration']) ? intval($_POST['duration']) : 0;
    $paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
    
    if ($duration <= 0) {
        $error = "Veuillez sélectionner une durée valide.";
    } elseif (empty($paymentMethod)) {
        $error = "Veuillez sélectionner un mode de paiement.";
    } else {
        // Calculer la nouvelle date d'expiration
        $currentEndDate = new DateTime($subscription['end_date']);
        $newEndDate = clone $currentEndDate;
        $newEndDate->modify("+{$duration} months");
        
        // Calculer le montant total
        $totalAmount = $subscription['price'] * $duration;
        
        // Simuler un paiement réussi
        $paymentSuccess = true;
        
        if ($paymentSuccess) {
            // Mettre à jour la date d'expiration de l'abonnement
            $stmt = $conn->prepare("UPDATE subscriptions SET end_date = ?, status = 'active', updated_at = NOW() WHERE id = ? AND user_id = ?");
            $newEndDateStr = $newEndDate->format('Y-m-d');
            $stmt->bind_param("sii", $newEndDateStr, $subscriptionId, $userId);
            
            if ($stmt->execute()) {
                // Enregistrer le paiement
                $stmt = $conn->prepare("INSERT INTO payments (user_id, subscription_id, amount, payment_date, payment_method, transaction_id, status) VALUES (?, ?, ?, NOW(), ?, ?, 'completed')");
                $transactionId = 'TRX' . time() . rand(1000, 9999);
                $stmt->bind_param("iidss", $userId, $subscriptionId, $totalAmount, $paymentMethod, $transactionId);
                $stmt->execute();
                
                $message = "Votre abonnement a été renouvelé avec succès jusqu'au " . date('d/m/Y', strtotime($newEndDateStr)) . ".";
                
                // Rediriger vers la page de détails de l'abonnement après 3 secondes
                header("refresh:3;url=subscription_details.php?id=" . $subscriptionId);
            } else {
                $error = "Une erreur est survenue lors du renouvellement de l'abonnement.";
            }
        } else {
            $error = "Le paiement a échoué. Veuillez réessayer ou contacter le support.";
        }
    }
}

// Calculer les prix pour différentes durées
$price1Month = $subscription['price'];
$price3Months = $subscription['price'] * 3 * 0.95; // 5% de réduction
$price6Months = $subscription['price'] * 6 * 0.9; // 10% de réduction
$price12Months = $subscription['price'] * 12 * 0.85; // 15% de réduction
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renouveler l'abonnement - HostWebsite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-white">
 

    <div class="container mx-auto py-10 px-4">
        <div class="flex items-center mb-8">
            <a href="subscription_details.php?id=<?php echo $subscriptionId; ?>" class="text-blue-400 hover:underline mr-4">
                &larr; Retour aux détails
            </a>
            <h1 class="text-3xl font-bold">Renouveler votre abonnement</h1>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="bg-green-900 border border-green-700 text-white px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-900 border border-red-700 text-white px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Formulaire de renouvellement -->
            <div class="md:col-span-2">
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="text-4xl mr-4"><?php echo getServiceIcon($subscription['service_type']); ?></div>
                        <div>
                            <h2 class="text-2xl font-bold"><?php echo getServiceName($subscription['service_type']); ?></h2>
                            <p class="text-gray-400"><?php echo htmlspecialchars($subscription['plan']); ?></p>
                        </div>
                    </div>
                    
                    <form method="POST" action="">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold mb-4">Choisissez la durée de renouvellement</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative bg-gray-700 p-4 rounded-lg border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all">
                                    <input type="radio" name="duration" value="1" class="absolute opacity-0" required>
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">1 mois</span>
                                        <span class="text-lg font-bold"><?php echo number_format($price1Month, 2); ?> €</span>
                                    </div>
                                    <p class="text-sm text-gray-400 mt-1">Facturation mensuelle</p>
                                </label>
                                
                                <label class="relative bg-gray-700 p-4 rounded-lg border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all">
                                    <input type="radio" name="duration" value="3" class="absolute opacity-0">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">3 mois</span>
                                        <span class="text-lg font-bold"><?php echo number_format($price3Months, 2); ?> €</span>
                                    </div>
                                    <p class="text-sm text-gray-400 mt-1">Économisez 5%</p>
                                </label>
                                
                                <label class="relative bg-gray-700 p-4 rounded-lg border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all">
                                    <input type="radio" name="duration" value="6" class="absolute opacity-0">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">6 mois</span>
                                        <span class="text-lg font-bold"><?php echo number_format($price6Months, 2); ?> €</span>
                                    </div>
                                    <p class="text-sm text-gray-400 mt-1">Économisez 10%</p>
                                </label>
                                
                                <label class="relative bg-gray-700 p-4 rounded-lg border-2 border-blue-500 cursor-pointer transition-all">
                                    <input type="radio" name="duration" value="12" class="absolute opacity-0" checked>
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">12 mois</span>
                                        <span class="text-lg font-bold"><?php echo number_format($price12Months, 2); ?> €</span>
                                    </div>
                                    <p class="text-sm text-gray-400 mt-1">Économisez 15% - Meilleure offre</p>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-bold mb-4">Mode de paiement</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative bg-gray-700 p-4 rounded-lg border-2 border-blue-500 cursor-pointer transition-all">
                                    <input type="radio" name="payment_method" value="card" class="absolute opacity-0" checked required>
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span class="font-medium">Carte bancaire</span>
                                    </div>
                                </label>
                                
                                <label class="relative bg-gray-700 p-4 rounded-lg border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all">
                                    <input type="radio" name="payment_method" value="paypal" class="absolute opacity-0">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.384a.64.64 0 0 1 .632-.537h6.012c2.658 0 4.53.625 5.255 1.757.62.963.6 1.978-.06 3.067-.668 1.107-1.919 1.933-3.508 2.31.686.293 1.216.701 1.572 1.214.375.54.501 1.218.375 2.018-.127.813-.518 1.544-1.155 2.167-.633.624-1.445 1.11-2.412 1.452-.955.336-2.04.507-3.235.507h-.61l-.483 3.914a.64.64 0 0 1-.632.546zm.1-2.62l.526-4.234a.33.33 0 0 1 .327-.282h1.367c1.103 0 1.978-.16 2.607-.48.619-.314 1.047-.774 1.274-1.366.213-.553.207-1.018-.02-1.393-.225-.373-.707-.56-1.433-.56H9.596l-1.092 8.316h-1.33v-.001zm2.354-9.179l.682-5.501a.33.33 0 0 1 .327-.281h1.523c.69 0 1.25.118 1.667.353.409.23.6.551.57.96-.033.445-.204.84-.51 1.177-.317.35-.823.623-1.508.814-.688.194-1.556.292-2.592.292H10.5l-.97 2.186zm11.447-6.866c-.523-.412-1.271-.62-2.232-.62h-3.255a.64.64 0 0 0-.633.537L11.74 21.337a.641.641 0 0 0 .633.74h3.209a.64.64 0 0 0 .632-.547l.483-3.913h1.37c1.196 0 2.281-.171 3.236-.507.967-.341 1.78-.828 2.412-1.452.637-.623 1.028-1.354 1.155-2.167.126-.8 0-1.477-.375-2.018-.357-.513-.886-.921-1.573-1.214 1.59-.377 2.84-1.203 3.508-2.31.66-1.09.68-2.104.06-3.067-.273-.424-.672-.786-1.185-1.077zM21.72 8.242c-.4.66-1.075 1.17-2.004 1.522-.93.35-2.112.528-3.516.528h-.864l.68-5.501a.33.33 0 0 1 .327-.281h1.43c1.25 0 2.103.209 2.552.623.445.41.558.986.337 1.723-.069.23-.163.444-.282.639.114.252.189.506.217.762.035.301.006.584-.087.847a2.168 2.168 0 0 1-.79.138zm-1.297 5.491c-.224.372-.706.558-1.433.558h-1.246l-.969 7.83a.33.33 0 0 1-.327.282h-2.35a.33.33 0 0 1-.326-.387l1.094-8.517a.33.33 0 0 1 .326-.281h2.336c.69 0 1.25.117 1.667.352.409.23.6.551.57.96-.033.445-.204.84-.51 1.177l.168.026z" />
                                        </svg>
                                        <span class="font-medium">PayPal</span>
                                    </div>
                                </label>
                                
                                <label class="relative bg-gray-700 p-4 rounded-lg border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="absolute opacity-0">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                        <span class="font-medium">Virement bancaire</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-bold mb-4">Informations de paiement</h3>
                            <div class="bg-gray-700 p-4 rounded-lg">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="card_number" class="block text-sm font-medium text-gray-300 mb-1">Numéro de carte</label>
                                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="expiry_date" class="block text-sm font-medium text-gray-300 mb-1">Date d'expiration</label>
                                            <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/AA" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label for="cvv" class="block text-sm font-medium text-gray-300 mb-1">CVV</label>
                                            <input type="text" id="cvv" name="cvv" placeholder="123" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="card_holder" class="block text-sm font-medium text-gray-300 mb-1">Titulaire de la carte</label>
                                        <input type="text" id="card_holder" name="card_holder" placeholder="John Doe" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition duration-300">
                                Procéder au paiement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Récapitulatif -->
            <div>
                <div class="bg-gray-800 rounded-lg p-6 sticky top-6">
                    <h2 class="text-xl font-bold mb-4">Récapitulatif</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span>Service</span>
                            <span><?php echo getServiceName($subscription['service_type']); ?></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Plan</span>
                            <span><?php echo htmlspecialchars($subscription['plan']); ?></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Prix mensuel</span>
                            <span><?php echo number_format($subscription['price'], 2); ?> €</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Date d'expiration actuelle</span>
                            <span><?php echo formatDate($subscription['end_date']); ?></span>
                        </div>
                        
                        <div class="border-t border-gray-700 pt-4">
                            <div class="flex justify-between font-bold">
                                <span>Total (12 mois)</span>
                                <span id="total-price"><?php echo number_format($price12Months, 2); ?> €</span>
                            </div>
                            <p class="text-sm text-gray-400 mt-1">TVA incluse</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm">Le renouvellement prendra effet à partir de la date d'expiration actuelle.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 =

    <script>
        // Mettre à jour le récapitulatif en fonction de la durée sélectionnée
        document.querySelectorAll('input[name="duration"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const duration = parseInt(this.value);
                let totalPrice = 0;
                
                switch(duration) {
                    case 1:
                        totalPrice = <?php echo $price1Month; ?>;
                        break;
                    case 3:
                        totalPrice = <?php echo $price3Months; ?>;
                        break;
                    case 6:
                        totalPrice = <?php echo $price6Months; ?>;
                        break;
                    case 12:
                        totalPrice = <?php echo $price12Months; ?>;
                        break;
                }
                
                document.getElementById('total-price').textContent = totalPrice.toFixed(2) + ' €';
                
                // Mettre en évidence l'option sélectionnée
                document.querySelectorAll('input[name="duration"]').forEach(r => {
                    r.closest('label').classList.remove('border-blue-500');
                    r.closest('label').classList.add('border-transparent');
                });
                this.closest('label').classList.remove('border-transparent');
                this.closest('label').classList.add('border-blue-500');
            });
        });
        
        // Mettre en évidence le mode de paiement sélectionné
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="payment_method"]').forEach(r => {
                    r.closest('label').classList.remove('border-blue-500');
                    r.closest('label').classList.add('border-transparent');
                });
                this.closest('label').classList.remove('border-transparent');
                this.closest('label').classList.add('border-blue-500');
            });
        });
    </script>
    <script src="assets/js/main.js"></script>
</body>
</html>

