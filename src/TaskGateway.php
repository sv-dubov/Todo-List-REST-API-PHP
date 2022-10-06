<?php

class TaskGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(int $user_id): array
    {
        $sql = "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY name";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['is_urgent'] = (bool) $row['is_urgent'];
            $row['is_personal'] = (bool) $row['is_personal'];
            $row['is_working'] = (bool) $row['is_working'];
            $row['is_done'] = (bool) $row['is_done'];
            $data[] = $row;
        }

        return $data;
    }

    public function getById(int $user_id, string $id): array | false
    {
        $sql = "SELECT * FROM tasks WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {
            $data['is_urgent'] = (bool) $data['is_urgent'];
            $data['is_personal'] = (bool) $data['is_personal'];
            $data['is_working'] = (bool) $data['is_working'];
            $data['is_done'] = (bool) $data['is_done'];
        }

        return $data;
    }

    public function create(int $user_id, array $data): string
    {
        $sql = "INSERT INTO tasks (name, description, is_urgent, is_personal, is_working, is_done, completed_at, user_id)
                VALUES (:name, :description, :is_urgent, :is_personal, :is_working, :is_done, :completed_at, :user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);

        if (empty($data["description"])) {
            $stmt->bindValue(":description", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":description", $data["description"], PDO::PARAM_STR);
        }

        $stmt->bindValue(":is_urgent", $data["is_urgent"] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(":is_personal", $data["is_personal"] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(":is_working", $data["is_working"] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(":is_done", $data["is_done"] ?? false, PDO::PARAM_BOOL);

        if (empty($data["completed_at"])) {
            $stmt->bindValue(":completed_at", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":completed_at", $data["completed_at"], PDO::PARAM_STR);
        }

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function update(int $user_id, string $id, array $data): int
    {
        $fields = [];

        if (!empty($data["name"])) {
            $fields["name"] = [$data["name"], PDO::PARAM_STR];
        }

        if (array_key_exists("description", $data)) {
            $fields["description"] = [
                $data["description"],
                $data["description"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            ];
        }

        if (array_key_exists("is_urgent", $data)) {
            $fields["is_urgent"] = [$data["is_urgent"], PDO::PARAM_BOOL];
        }

        if (array_key_exists("is_personal", $data)) {
            $fields["is_personal"] = [$data["is_personal"], PDO::PARAM_BOOL];
        }

        if (array_key_exists("is_working", $data)) {
            $fields["is_working"] = [$data["is_working"], PDO::PARAM_BOOL];
        }

        if (array_key_exists("is_done", $data)) {
            $fields["is_done"] = [$data["is_done"], PDO::PARAM_BOOL];
        }

        if (!empty($data["completed_at"])) {
            $fields["completed_at"] = [$data["completed_at"], PDO::PARAM_STR];
        }

        if (!empty($fields)) {
            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql = "UPDATE tasks" . " SET " . implode(", ", $sets) . " WHERE id = :id" . " AND user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

            foreach ($fields as $name => $values) {
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();
            return $stmt->rowCount();
        } else {
            return 0;
        }
    }

    public function delete(int $user_id, string $id): int
    {
        $sql = "DELETE FROM tasks WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
