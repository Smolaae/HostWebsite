<?php
require_once __DIR__ . '/database.php';

class TicketManager {
    private $db;
    private $useDatabase;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->useDatabase = $this->db->isConnected();
    }

    public function generateTicketId() {
        return 'TK' . date('Ymd') . rand(1000, 9999);
    }

    public function createTicket($data) {
        if ($this->useDatabase) {
            return $this->createTicketDB($data);
        } else {
            return $this->createTicketJSON($data);
        }
    }

    public function getUserTickets($email) {
        if ($this->useDatabase) {
            return $this->getUserTicketsDB($email);
        } else {
            return $this->getUserTicketsJSON($email);
        }
    }

    public function getTicketById($ticketId) {
        if ($this->useDatabase) {
            return $this->getTicketByIdDB($ticketId);
        } else {
            return $this->getTicketByIdJSON($ticketId);
        }
    }

    public function addResponse($ticketId, $author, $authorType, $message) {
        if ($this->useDatabase) {
            return $this->addResponseDB($ticketId, $author, $authorType, $message);
        } else {
            return $this->addResponseJSON($ticketId, $author, $authorType, $message);
        }
    }

    public function updateTicketStatus($ticketId, $status) {
        if ($this->useDatabase) {
            return $this->updateTicketStatusDB($ticketId, $status);
        } else {
            return $this->updateTicketStatusJSON($ticketId, $status);
        }
    }

    public function getAllTickets() {
        if ($this->useDatabase) {
            return $this->getAllTicketsDB();
        } else {
            return $this->getAllTicketsJSON();
        }
    }

    public function getUser($email) {
        if ($this->useDatabase) {
            return $this->getUserDB($email);
        } else {
            return $this->getUserJSON($email);
        }
    }

    public function createUser($name, $email) {
        if ($this->useDatabase) {
            return $this->createUserDB($name, $email);
        } else {
            return $this->createUserJSON($name, $email);
        }
    }

    // Méthodes pour base de données MySQL
    private function createTicketDB($data) {
        try {
            $pdo = $this->db->getConnection();
            
            // Créer ou récupérer l'utilisateur
            $user = $this->createUser($data['name'], $data['email']);
            
            $ticketId = $this->generateTicketId();
            
            $stmt = $pdo->prepare("
                INSERT INTO tickets (id, user_id, name, email, subject, category, priority, message, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Ouvert')
            ");
            
            $stmt->execute([
                $ticketId,
                $user['id'],
                $data['name'],
                $data['email'],
                $data['subject'],
                $data['category'],
                $data['priority'],
                $data['message']
            ]);
            
            return $this->getTicketByIdDB($ticketId);
        } catch (Exception $e) {
            return false;
        }
    }

    private function getUserTicketsDB($email) {
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare("
                SELECT t.*, 
                       (SELECT COUNT(*) FROM ticket_responses tr WHERE tr.ticket_id = t.id) as response_count
                FROM tickets t 
                WHERE t.email = ? 
                ORDER BY t.created_at DESC
            ");
            $stmt->execute([$email]);
            $tickets = $stmt->fetchAll();
            
            // Ajouter les réponses pour chaque ticket
            foreach ($tickets as &$ticket) {
                $ticket['responses'] = $this->getTicketResponsesDB($ticket['id']);
            }
            
            return $tickets;
        } catch (Exception $e) {
            return [];
        }
    }

    private function getTicketByIdDB($ticketId) {
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
            $stmt->execute([$ticketId]);
            $ticket = $stmt->fetch();
            
            if ($ticket) {
                $ticket['responses'] = $this->getTicketResponsesDB($ticketId);
            }
            
            return $ticket;
        } catch (Exception $e) {
            return false;
        }
    }

    private function getTicketResponsesDB($ticketId) {
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare("
                SELECT * FROM ticket_responses 
                WHERE ticket_id = ? 
                ORDER BY created_at ASC
            ");
            $stmt->execute([$ticketId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function addResponseDB($ticketId, $author, $authorType, $message) {
        try {
            $pdo = $this->db->getConnection();
            
            // Ajouter la réponse
            $stmt = $pdo->prepare("
                INSERT INTO ticket_responses (ticket_id, author, author_type, message) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$ticketId, $author, $authorType, $message]);
            
            // Mettre à jour le statut du ticket
            $newStatus = $authorType === 'admin' ? 'Répondu' : 'En attente de réponse';
            $this->updateTicketStatusDB($ticketId, $newStatus);
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function updateTicketStatusDB($ticketId, $status) {
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare("UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$status, $ticketId]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function getAllTicketsDB() {
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare("
                SELECT t.*, 
                       (SELECT COUNT(*) FROM ticket_responses tr WHERE tr.ticket_id = t.id) as response_count
                FROM tickets t 
                ORDER BY t.created_at DESC
            ");
            $stmt->execute();
            $tickets = $stmt->fetchAll();
            
            // Ajouter les réponses pour chaque ticket
            foreach ($tickets as &$ticket) {
                $ticket['responses'] = $this->getTicketResponsesDB($ticket['id']);
            }
            
            return $tickets;
        } catch (Exception $e) {
            return [];
        }
    }

    private function getUserDB($email) {
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }

    private function createUserDB($name, $email) {
        try {
            $pdo = $this->db->getConnection();
            
            // Vérifier si l'utilisateur existe déjà
            $user = $this->getUserDB($email);
            if ($user) {
                return $user;
            }
            
            // Créer le nouvel utilisateur
            $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            $stmt->execute([$name, $email]);
            
            return $this->getUserDB($email);
        } catch (Exception $e) {
            return false;
        }
    }

    // Méthodes pour fallback JSON
    private function createTicketJSON($data) {
        $tickets = loadJsonData('tickets.json');
        $users = loadJsonData('users.json');
        
        $ticketId = $this->generateTicketId();
        
        $ticket = [
            'id' => $ticketId,
            'user_id' => null,
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'category' => $data['category'],
            'priority' => $data['priority'],
            'message' => $data['message'],
            'status' => 'Ouvert',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'responses' => []
        ];
        
        $tickets[] = $ticket;
        saveJsonData('tickets.json', $tickets);
        
        // Créer l'utilisateur si nécessaire
        if (!isset($users[$data['email']])) {
            $users[$data['email']] = [
                'id' => count($users) + 1,
                'name' => $data['name'],
                'email' => $data['email'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            saveJsonData('users.json', $users);
        }
        
        return $ticket;
    }

    private function getUserTicketsJSON($email) {
        $tickets = loadJsonData('tickets.json');
        return array_filter($tickets, function($ticket) use ($email) {
            return $ticket['email'] === $email;
        });
    }

    private function getTicketByIdJSON($ticketId) {
        $tickets = loadJsonData('tickets.json');
        foreach ($tickets as $ticket) {
            if ($ticket['id'] === $ticketId) {
                return $ticket;
            }
        }
        return false;
    }

    private function addResponseJSON($ticketId, $author, $authorType, $message) {
        $tickets = loadJsonData('tickets.json');
        
        for ($i = 0; $i < count($tickets); $i++) {
            if ($tickets[$i]['id'] === $ticketId) {
                $tickets[$i]['responses'][] = [
                    'id' => count($tickets[$i]['responses']) + 1,
                    'ticket_id' => $ticketId,
                    'author' => $author,
                    'author_type' => $authorType,
                    'message' => $message,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $tickets[$i]['updated_at'] = date('Y-m-d H:i:s');
                $tickets[$i]['status'] = $authorType === 'admin' ? 'Répondu' : 'En attente de réponse';
                break;
            }
        }
        
        saveJsonData('tickets.json', $tickets);
        return true;
    }

    private function updateTicketStatusJSON($ticketId, $status) {
        $tickets = loadJsonData('tickets.json');
        
        for ($i = 0; $i < count($tickets); $i++) {
            if ($tickets[$i]['id'] === $ticketId) {
                $tickets[$i]['status'] = $status;
                $tickets[$i]['updated_at'] = date('Y-m-d H:i:s');
                break;
            }
        }
        
        saveJsonData('tickets.json', $tickets);
        return true;
    }

    private function getAllTicketsJSON() {
        return loadJsonData('tickets.json');
    }

    private function getUserJSON($email) {
        $users = loadJsonData('users.json');
        return isset($users[$email]) ? $users[$email] : false;
    }

    private function createUserJSON($name, $email) {
        $users = loadJsonData('users.json');
        
        if (!isset($users[$email])) {
            $users[$email] = [
                'id' => count($users) + 1,
                'name' => $name,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s')
            ];
            saveJsonData('users.json', $users);
        }
        
        return $users[$email];
    }
}
?>
