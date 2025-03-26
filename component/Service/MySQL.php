<?php

declare(strict_types=1);

namespace Phant\Client\Service;

use PDO;
use Phant\Error\NotFound;

class MySQL implements \Phant\Client\Port\MySQL
{
    protected ?PDO $pdo;

    public function __construct(
        private readonly string $host,
        private readonly string $dbname,
        private readonly string $user,
        private readonly string $pass,
        private readonly int $port = 3306,
        private readonly string $charset = 'utf8mb4',
        private readonly ?array $config = [
            PDO::ATTR_EMULATE_PREPARES		=> false,
            PDO::MYSQL_ATTR_DIRECT_QUERY	=> false,
            PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION
        ]
    ) {
        $this->pdo = null;
    }

    public function execute(
        string $query,
        array $values = []
    ): void {
        $statement = $this->getPdo()->prepare($query);
        $this->bindParams($statement, $values);
        $statement->execute();
    }

    public function getList(
        string $query,
        array $values = []
    ): array {
        $statement = $this->getPdo()->prepare($query);
        $this->bindParams($statement, $values);
        $statement->execute();
        $all = $statement->fetchAll();

        if ($statement->rowCount() == 0) {
            throw new NotFound();
        }

        return $all;
    }

    public function getListByColumnValue(
        string $table,
        string $column,
        string|int|float|bool $value
    ): array {
        return $this->getList(
            'SELECT * FROM `' . $table . '` WHERE `' . $column . '` = :value',
            [
                ':value'	=> $value,
            ]
        );
    }

    public function get(
        string $query,
        array $values = []
    ): array {
        $statement = $this->getPdo()->prepare($query);
        $this->bindParams($statement, $values);
        $statement->execute();
        $one = $statement->fetch();

        if ($statement->rowCount() == 0) {
            throw new NotFound();
        }

        return $one;
    }

    public function getByColumnValue(
        string $table,
        string $column,
        string|int|float|bool $value
    ): array {
        return $this->get(
            'SELECT * FROM `' . $table . '` WHERE `' . $column . '` = :value',
            [
                ':value'	=> $value,
            ]
        );
    }

    public function exist(
        string $query,
        array $values = []
    ): bool {
        $statement = $this->getPdo()->prepare($query);
        $this->bindParams($statement, $values);
        $statement->execute();
        $exist = $statement->fetch(PDO::FETCH_ASSOC);

        return ($exist != false);
    }

    public function existByColumnValue(
        string $table,
        string $column,
        string|int|float|bool $value
    ): bool {
        return $this->exist(
            'SELECT * FROM `' . $table . '` WHERE `' . $column . '` = :value',
            [
                ':value'	=> $value,
            ]
        );
    }

    private function bindParams(
        &$statement,
        array $values
    ): void {
        foreach ($values as $param => $value) {
            if (is_object($value)) {
                $value = (string) $value;
            }
            $statement->bindParam($param, $value, $this->getParamType($value));
        }
    }

    private function getParamType(
        mixed $value
    ): int {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        }
        if (is_float($value)) {
            return PDO::PARAM_INT;
        }
        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        }
        if (is_null($value)) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }

    protected function getPdo(
    ): PDO {
        $this->pdo ??= new PDO(
            'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';port=' . $this->port . ';charset=' . $this->charset,
            $this->user,
            $this->pass,
            $this->config
        );

        return $this->pdo;
    }
}
