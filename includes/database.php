<?php
// includes/database.php
// Digital History - Database Connection

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHARSET
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function insert($table, $data) {
        $columns = implode('`, `', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO `$table` (`$columns`) VALUES ($placeholders)";
        $this->query($sql, $data);
        return $this->connection->lastInsertId();
    }
    
    public function update($table, $data, $where, $whereParams = []) {
        $sets = [];
        foreach ($data as $key => $value) {
            $sets[] = "`$key` = :$key";
        }
        
        // Convert positional parameters to named parameters for consistency
        if (!empty($whereParams)) {
            // If where contains ? placeholders, convert to named parameters
            $whereParts = explode('?', $where);
            $namedWhere = '';
            $paramIndex = 0;
            
            foreach ($whereParts as $index => $part) {
                $namedWhere .= $part;
                if ($index < count($whereParts) - 1) {
                    $paramName = 'where_param_' . $paramIndex;
                    $namedWhere .= ':' . $paramName;
                    $data[$paramName] = $whereParams[$paramIndex];
                    $paramIndex++;
                }
            }
            $where = $namedWhere;
        }
        
        $sql = "UPDATE `$table` SET " . implode(', ', $sets) . " WHERE $where";
        return $this->query($sql, $data);
    }
    
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM `$table` WHERE $where";
        return $this->query($sql, $params);
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
        return $this->connection->commit();
    }
    
    public function rollBack() {
        return $this->connection->rollBack();
    }
}

// Helper function for quick database access
function db() {
    return Database::getInstance();
}
?>