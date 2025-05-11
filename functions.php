<?php
// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour authentifier un utilisateur
function authenticateUser($email, $password, $conn) {
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
    }
    
    return false;
}

// Fonction pour déconnecter un utilisateur
function logoutUser() {
    session_unset();
    session_destroy();
}

// Fonction pour enregistrer un nouvel utilisateur
function registerUser($username, $email, $password, $conn) {
    // Vérifier si l'email existe déjà
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return false; // L'email existe déjà
    }
    
    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insérer le nouvel utilisateur
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Fonction pour récupérer les informations d'un utilisateur
function getUserInfo($userId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Fonction pour récupérer les abonnements d'un utilisateur
function getUserSubscriptions($userId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE user_id = ? ORDER BY status DESC, end_date DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fonction pour récupérer un abonnement spécifique
function getSubscription($subscriptionId, $userId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $subscriptionId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Fonction pour mettre à jour les informations d'un utilisateur
function updateUserInfo($userId, $data, $conn) {
    $query = "UPDATE users SET ";
    $params = [];
    $types = "";
    
    foreach ($data as $key => $value) {
        $query .= "$key = ?, ";
        $params[] = $value;
        $types .= "s"; // Supposer que tous les paramètres sont des chaînes
    }
    
    $query = rtrim($query, ", ");
    $query .= " WHERE id = ?";
    $params[] = $userId;
    $types .= "i";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    return $stmt->execute();
}

// Fonction pour formater la date
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

// Fonction pour obtenir l'icône du service
function getServiceIcon($serviceType) {
    switch($serviceType) {
        case 'fivem':
            return '🎮';
        case 'minecraft':
            return '⛏️';
        case 'gmod':
            return '🔧';
        case 'linux':
            return '🐧';
        case 'windows':
            return '🪟';
        default:
            return '🖥️';
    }
}

// Fonction pour obtenir le nom complet du service
function getServiceName($serviceType) {
    switch($serviceType) {
        case 'fivem':
            return 'FiveM';
        case 'minecraft':
            return 'Minecraft';
        case 'gmod':
            return 'Garry\'s Mod';
        case 'linux':
            return 'VPS Linux';
        case 'windows':
            return 'VPS Windows';
        default:
            return $serviceType;
    }
}

// Fonction pour vérifier si une chaîne est valide (non vide après nettoyage)
function isValidString($str) {
    return !empty(trim($str));
}

// Fonction pour nettoyer les entrées utilisateur
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
