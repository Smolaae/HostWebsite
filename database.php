<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'hostwebsite');
define('DB_USER', 'root'); // Changez selon votre configuration
define('DB_PASS', ''); // Changez selon votre configuration
define('DB_CHARSET', 'utf8mb4');

// Mot de passe admin
define('ADMIN_PASSWORD', 'admin123'); // Changez en production

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            // Si la connexion échoue, on utilisera le système JSON
            $this->pdo = null;
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function isConnected() {
        return $this->pdo !== null;
    }
}

// Fonctions utilitaires pour le fallback JSON
function getJsonPath($file) {
    $dir = __DIR__ . '/../data';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    return $dir . '/' . $file;
}

function loadJsonData($file) {
    $path = getJsonPath($file);
    if (!file_exists($path)) {
        file_put_contents($path, json_encode([]));
        return [];
    }
    $content = file_get_contents($path);
    return json_decode($content, true) ?: [];
}

function saveJsonData($file, $data) {
    $path = getJsonPath($file);
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}
?>