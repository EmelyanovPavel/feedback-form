<?php
declare(strict_types=1);

namespace Model;

use Config\Database;
use InvalidArgumentException;
use PDO;

class FeedbackModel
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Валидация полей (серверная)
     */
    private function validate(string $fullName, string $email, string $message): void
    {
        if (trim($fullName) === '' || mb_strlen($fullName) > 100) {
            throw new InvalidArgumentException('Invalid full name.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address.');
        }

        if (trim($message) === '' || mb_strlen($message) > 65535) {
            throw new InvalidArgumentException('Invalid message.');
        }
    }

    public function save(string $fullName, string $email, string $message): bool
    {
        $this->validate($fullName, $email, $message);

        $sql = 'INSERT INTO feedbacks (full_name, email, message) VALUES (:full_name, :email, :message)';
        $stmt = $this->db->getPdo()->prepare($sql);

        // bindValue защищает от SQL-инъекций
        $stmt->bindValue(':full_name', $fullName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':message', $message, PDO::PARAM_STR);

        return (bool)$stmt->execute();
    }

    public function getAll(): array
    {
        $sql = 'SELECT id, full_name, email, message, created_at FROM feedbacks ORDER BY created_at DESC';
        $stmt = $this->db->getPdo()->query($sql);
        return $stmt->fetchAll();
    }
} 
?>