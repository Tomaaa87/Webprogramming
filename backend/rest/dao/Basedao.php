<?php
require_once __DIR__ . '/../config.php';


class BaseDao {
    protected $table;
    protected $connection;

    public function __construct($table) {
        $this->table = $table;
        $this->connection = Database::connect();
    }

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id, $pk = 'id') {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE {$pk} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
        return $this->connection->lastInsertId();
    }

    public function update($id, $data, $pk = 'id') {
        $fields = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $fields WHERE {$pk} = :id";
        $data['id'] = $id;
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id, $pk = 'id') {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE {$pk} = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function query($sql, $params = []) {
    $stmt = $this->connection->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
    }

    public function query_unique($sql, $params = []) {
    $stmt = $this->connection->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
    }

    public function query_execute($sql, $params = []) {
    $stmt = $this->connection->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
    }

}
?>