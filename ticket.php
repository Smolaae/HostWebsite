
   <?php
session_start();
require_once 'TicketManager.php';

$ticketManager = new TicketManager();

$message = '';
$messageType = '';

if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_ticket':
                $ticketData = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'subject' => $_POST['subject'],
                    'category' => $_POST['category'],
                    'priority' => $_POST['priority'],
                    'message' => $_POST['message']
                ];
                
                $ticket = $ticketManager->createTicket($ticketData);
                
                if ($ticket) {
                    $_SESSION['user_email'] = $_POST['email'];
                    $_SESSION['user_name'] = $_POST['name'];
                    
                    $message = "Votre ticket #{$ticket['id']} a été créé avec succès !";
                    $messageType = 'success';
                } else {
                    $message = "Erreur lors de la création du ticket.";
                    $messageType = 'error';
                }
                break;
                
            case 'add_response':
                if (isset($_SESSION['user_email'])) {
                    $success = $ticketManager->addResponse(
                        $_POST['ticket_id'],
                        $_SESSION['user_name'],
                        'client',
                        $_POST['response_message']
                    );
                    
                    if ($success) {
                        $message = "Votre réponse a été ajoutée au ticket.";
                        $messageType = 'success';
                    } else {
                        $message = "Erreur lors de l'ajout de la réponse.";
                        $messageType = 'error';
                    }
                }
                break;
                
            case 'login':
                $user = $ticketManager->getUser($_POST['email']);

                if ($user) {
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = isset($user['username']) ? $user['username'] : 'Utilisateur';
                    // $_SESSION['user_type'] = $user['type'] ?? 'client'; // Par défaut, type client
                    $_SESSION['user_password'] = $user['password']; // Pour vérification ultérieure
                    $message = "Connexion réussie !";
                    $messageType = 'success';
                }

                 else {
                    $message = "Aucun compte trouvé avec cet email.";
                    $messageType = 'error';
                }
                break;

                
            case 'logout':
                session_destroy();
                header('Location: ticket.php');
                exit;
        }
    }
}

// Récupérer les tickets de l'utilisateur connecté
$userTickets = [];
if (isset($_SESSION['user_email'])) {
    $userTickets = $ticketManager->getUserTickets($_SESSION['user_email']);
    usort($userTickets, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support - LaeHosting</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-dark text-white min-h-screen">
    <!-- Header -->
    <header class="bg-dark-light shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-server text-primary text-2xl mr-2"></i>
                    <span class="text-2xl font-bold">LaeHosting</span>
                    <span class="ml-4 text-gray-400">/ Support</span>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <span class="text-gray-300">Bonjour, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="text-red-400 hover:text-red-300">
                                <i class="fas fa-sign-out-alt mr-1"></i> Déconnexion
                            </button>
                        </form>
                    <?php endif; ?>
                    <a href="index.php" class="text-gray-300 hover:text-primary">
                        <i class="fas fa-home mr-1"></i> Accueil
                    </a>
                    <a href="admin-tickets.php" class="text-gray-300 hover:text-primary">
                        <i class="fas fa-cog mr-1"></i> Admin
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-600' : 'bg-red-600' ?>">
                <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_email'])): ?>
            <!-- Section de connexion/création de ticket -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Connexion -->
                <div class="bg-dark-light p-6 rounded-lg">
                    <h2 class="text-2xl font-bold mb-4">
                        <i class="fas fa-sign-in-alt text-primary mr-2"></i>
                        Connexion
                    </h2>
                    <p class="text-gray-400 mb-6">Connectez-vous pour voir vos tickets existants</p>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="login">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" required 
                                   class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Mot de passe</label>
                            <input type="password" name="password" required 
                                   class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <button type="submit" class="w-full bg-primary hover:bg-purple-700 text-white py-2 rounded-md font-semibold transition-colors">
                            Se connecter
                        </button>
                    </form>
                </div>

                <!-- Nouveau ticket -->
                <div class="bg-dark-light p-6 rounded-lg">
                    <h2 class="text-2xl font-bold mb-4">
                        <i class="fas fa-plus-circle text-primary mr-2"></i>
                        Nouveau Ticket
                    </h2>
                    <p class="text-gray-400 mb-6">Créez un nouveau ticket de support</p>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="create_ticket">
                        
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Nom *</label>
                                <input type="text" name="name" required 
                                       class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Email *</label>
                                <input type="email" name="email" required 
                                       class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Sujet *</label>
                            <input type="text" name="subject" required 
                                   class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Catégorie *</label>
                                <select name="category" required 
                                        class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Sélectionner...</option>
                                    <option value="technique">Problème technique</option>
                                    <option value="facturation">Facturation</option>
                                    <option value="configuration">Configuration</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Priorité *</label>
                                <select name="priority" required 
                                        class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Sélectionner...</option>
                                    <option value="faible">Faible</option>
                                    <option value="normale">Normale</option>
                                    <option value="haute">Haute</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Message *</label>
                            <textarea name="message" rows="4" required 
                                      class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                      placeholder="Décrivez votre problème en détail..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-purple-700 text-white py-3 rounded-md font-semibold transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Créer le ticket
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Section des tickets pour utilisateur connecté -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Liste des tickets -->
                <div class="lg:col-span-2">
                    <div class="bg-dark-light p-6 rounded-lg">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">
                                <i class="fas fa-ticket-alt text-primary mr-2"></i>
                                Mes Tickets
                            </h2>
                            <button onclick="showNewTicketForm()" class="bg-primary hover:bg-purple-700 text-white px-4 py-2 rounded-md font-semibold transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Nouveau Ticket
                            </button>
                        </div>

                        <?php if (empty($userTickets)): ?>
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>Aucun ticket trouvé</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($userTickets as $ticket): ?>
                                    <div class="bg-dark p-4 rounded-lg border-l-4 <?php
                                        switch($ticket['status']) {
                                            case 'Ouvert': echo 'border-blue-500'; break;
                                            case 'En cours': echo 'border-yellow-500'; break;
                                            case 'Répondu': echo 'border-green-500'; break;
                                            case 'Résolu': echo 'border-green-500'; break;
                                            case 'Fermé': echo 'border-gray-500'; break;
                                            default: echo 'border-blue-500';
                                        }
                                    ?> cursor-pointer hover:bg-dark-lighter transition-colors" 
                                         onclick="showTicketDetails('<?= $ticket['id'] ?>')">
                                        
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-bold text-lg"><?= htmlspecialchars($ticket['subject']) ?></h3>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?php
                                                switch($ticket['status']) {
                                                    case 'Ouvert': echo 'bg-blue-600 text-blue-100'; break;
                                                    case 'En cours': echo 'bg-yellow-600 text-yellow-100'; break;
                                                    case 'Répondu': echo 'bg-green-600 text-green-100'; break;
                                                    case 'Résolu': echo 'bg-green-600 text-green-100'; break;
                                                    case 'Fermé': echo 'bg-gray-600 text-gray-100'; break;
                                                    default: echo 'bg-blue-600 text-blue-100';
                                                }
                                            ?>">
                                                <?= htmlspecialchars($ticket['status']) ?>
                                            </span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center text-sm text-gray-400">
                                            <span>
                                                <i class="fas fa-hashtag mr-1"></i>
                                                <?= htmlspecialchars($ticket['id']) ?>
                                            </span>
                                            <span>
                                                <i class="fas fa-clock mr-1"></i>
                                                <?= date('d/m/Y H:i', strtotime($ticket['created_at'])) ?>
                                            </span>
                                        </div>
                                        
                                        <div class="mt-2 flex items-center space-x-4 text-xs">
                                            <span class="px-2 py-1 bg-dark-lighter rounded">
                                                <?= htmlspecialchars($ticket['category']) ?>
                                            </span>
                                            <span class="px-2 py-1 bg-dark-lighter rounded">
                                                Priorité: <?= htmlspecialchars($ticket['priority']) ?>
                                            </span>
                                            <?php if (!empty($ticket['responses'])): ?>
                                                <span class="text-green-400">
                                                    <i class="fas fa-comments mr-1"></i>
                                                    <?= count($ticket['responses']) ?> réponse(s)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Détails du ticket -->
                <div class="lg:col-span-1">
                    <div id="ticket-details" class="bg-dark-light p-6 rounded-lg hidden">
                        <h3 class="text-xl font-bold mb-4">Détails du Ticket</h3>
                        <div id="ticket-content"></div>
                    </div>

                    <!-- Formulaire nouveau ticket (caché par défaut) -->
                    <div id="new-ticket-form" class="bg-dark-light p-6 rounded-lg hidden">
                        <h3 class="text-xl font-bold mb-4">Nouveau Ticket</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="create_ticket">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['user_email']) ?>">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($_SESSION['user_name']) ?>">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Sujet *</label>
                                <input type="text" name="subject" required 
                                       class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Catégorie *</label>
                                <select name="category" required 
                                        class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Sélectionner...</option>
                                    <option value="technique">Problème technique</option>
                                    <option value="facturation">Facturation</option>
                                    <option value="configuration">Configuration</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Priorité *</label>
                                <select name="priority" required 
                                        class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Sélectionner...</option>
                                    <option value="faible">Faible</option>
                                    <option value="normale">Normale</option>
                                    <option value="haute">Haute</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium mb-2">Message *</label>
                                <textarea name="message" rows="4" required 
                                          class="w-full px-4 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                          placeholder="Décrivez votre problème..."></textarea>
                            </div>

                            <div class="flex space-x-2">
                                <button type="submit" class="flex-1 bg-primary hover:bg-purple-700 text-white py-2 rounded-md font-semibold transition-colors">
                                    Créer
                                </button>
                                <button type="button" onclick="hideNewTicketForm()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const tickets = <?= json_encode($userTickets) ?>;

        function showTicketDetails(ticketId) {
            const ticket = tickets.find(t => t.id === ticketId);
            if (!ticket) return;

            const detailsDiv = document.getElementById('ticket-details');
            const contentDiv = document.getElementById('ticket-content');
            
            let statusColor = '';
            switch(ticket.status) {
                case 'Ouvert': statusColor = 'text-blue-400'; break;
                case 'En cours': statusColor = 'text-yellow-400'; break;
                case 'Répondu': statusColor = 'text-green-400'; break;
                case 'Résolu': statusColor = 'text-green-400'; break;
                case 'Fermé': statusColor = 'text-gray-400'; break;
                default: statusColor = 'text-blue-400';
            }

            let responsesHtml = '';
            if (ticket.responses && ticket.responses.length > 0) {
                responsesHtml = '<div class="mt-6"><h4 class="font-bold mb-3">Conversation</h4><div class="space-y-3">';
                ticket.responses.forEach(response => {
                    const isAdmin = response.author_type === 'admin';
                    responsesHtml += `
                        <div class="p-3 rounded-lg ${isAdmin ? 'bg-primary/20 border-l-4 border-primary' : 'bg-dark border-l-4 border-gray-500'}">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold ${isAdmin ? 'text-primary' : 'text-gray-300'}">
                                    ${isAdmin ? '🛠️ ' : '👤 '}${response.author}
                                </span>
                                <span class="text-xs text-gray-400">${new Date(response.created_at).toLocaleString('fr-FR')}</span>
                            </div>
                            <p class="text-gray-300">${response.message}</p>
                        </div>
                    `;
                });
                responsesHtml += '</div></div>';
            }

            contentDiv.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="font-bold text-lg">${ticket.subject}</h4>
                        <p class="text-sm text-gray-400">#${ticket.id}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Statut:</span>
                            <span class="${statusColor} font-semibold">${ticket.status}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Priorité:</span>
                            <span class="text-white">${ticket.priority}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Catégorie:</span>
                            <span class="text-white">${ticket.category}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Créé le:</span>
                            <span class="text-white">${new Date(ticket.created_at).toLocaleDateString('fr-FR')}</span>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold mb-2">Message initial:</h5>
                        <div class="bg-dark p-3 rounded-lg">
                            <p class="text-gray-300">${ticket.message}</p>
                        </div>
                    </div>
                    
                    ${responsesHtml}
                    
                    ${ticket.status !== 'Fermé' ? `
                        <div class="mt-6">
                            <h5 class="font-semibold mb-2">Ajouter une réponse:</h5>
                            <form method="POST">
                                <input type="hidden" name="action" value="add_response">
                                <input type="hidden" name="ticket_id" value="${ticket.id}">
                                <textarea name="response_message" rows="3" required 
                                          class="w-full px-3 py-2 bg-dark border border-dark-lighter rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                          placeholder="Votre réponse..."></textarea>
                                <button type="submit" class="mt-2 w-full bg-primary hover:bg-purple-700 text-white py-2 rounded-md font-semibold transition-colors text-sm">
                                    Envoyer
                                </button>
                            </form>
                        </div>
                    ` : ''}
                </div>
            `;
            
            detailsDiv.classList.remove('hidden');
            document.getElementById('new-ticket-form').classList.add('hidden');
        }

        function showNewTicketForm() {
            document.getElementById('new-ticket-form').classList.remove('hidden');
            document.getElementById('ticket-details').classList.add('hidden');
        }

        function hideNewTicketForm() {
            document.getElementById('new-ticket-form').classList.add('hidden');
        }
    </script>
</body>
</html>
