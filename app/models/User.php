<?php

class User{

    public $id;
    public $name;
    public $email;
    public $role;
    public $password;

    private PDO $db;
    private string $table = "users";

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function createCustomer(string $name, string $email, string $password): bool
    {
        $check = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $check->bindParam(':email', $email);
        $check->execute();

        if ($check->fetch()) {
            return false;
        }

        $sql = "INSERT INTO users (name, email, role, password)
                VALUES (:name, :email, 'customer', :password)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }

    public function create(): bool
    {
        $sql = "INSERT INTO {$this->table}
                (name, email, role, password)
                VALUES (:name, :email, :role, :password)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name'     => trim($this->name),
            ':email'    => trim($this->email),
            ':role'     => $this->role,
            ':password' => trim($this->password)
        ]);
    }

    public function read(): PDOStatement
    {
        $sql = "SELECT id, name, email, role FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function readPaging(int $from, int $limit): PDOStatement
    {
        $sql = "SELECT id, name, email, role
                FROM {$this->table}
                ORDER BY id ASC
                LIMIT :from, :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':from', $from, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function getUserById(): ?array
    {
        $sql = "SELECT id, name, email, role FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update(): bool
    {
        if ($this->password) {
            $sql = "UPDATE {$this->table}
                    SET name = :name, email = :email, role = :role, password = :password
                    WHERE id = :id";

            return $this->db->prepare($sql)->execute([
                ':name'     => $this->name,
                ':email'    => $this->email,
                ':role'     => $this->role,
                ':password' => $this->password,
                ':id'       => $this->id
            ]);
        }

        /* Update without changing password */
        $sql = "UPDATE {$this->table}
                SET name = :name, email = :email, role = :role
                WHERE id = :id";

        return $this->db->prepare($sql)->execute([
            ':name'  => $this->name,
            ':email' => $this->email,
            ':role'  => $this->role,
            ':id'    => $this->id
        ]);
    }

    public function deleteById(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getById()
    {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
