<?php
declare(strict_types=1);

namespace Phant\Client;

use PDO;

use Phant\Error\NotFound;

class MySQL
{
	protected PDO $pdo;
	
	public function __construct(
		string $host,
		string $dbname,
		string $user,
		string $pass,
		string $port = '3306',
		string $charset = 'utf8mb4'
	)
	{
		$this->pdo = new PDO(
			'mysql:host=' . $host . ';dbname=' . $dbname . ';port=' . $port . ';charset=' . $charset, 
			$user, 
			$pass, 
			[
				PDO::ATTR_EMULATE_PREPARES		=> false,
				PDO::MYSQL_ATTR_DIRECT_QUERY	=> false,
				PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION
			]
		);
	}
	
	public function execute(string $query, array $values = []): void
	{
		$query = $this->pdo->prepare($query);
		$query->execute($values);
	}
	
	public function getList(string $query, array $values = []): array
	{
		$query = $this->pdo->prepare($query);
		$query->execute($values);
		$all = $query->fetchAll();
		
		if ($query->rowCount() == 0) {
			throw new NotFound;
		}
		
		return $all;
	}
	
	public function getListByColumnValue(string $table, string $column, string|int|float|bool $value): array
	{
		return $this->getList('
			SELECT * 
			FROM `' . $table . '` 
			WHERE `' . $column . '` = :value
		', [
			':value'	=> $value,
		]);
	}
	
	public function get(string $query, array $values = []): array
	{
		$query = $this->pdo->prepare($query);
		$query->execute($values);
		$one = $query->fetch();
		
		if ($query->rowCount() == 0) {
			throw new NotFound;
		}
		
		return $one;
	}
	
	public function getByColumnValue(string $table, string $column, string|int|float|bool $value): array
	{
		return $this->get('
			SELECT * 
			FROM `' . $table . '` 
			WHERE `' . $column . '` = :value
		', [
			':value'	=> $value,
		]);
	}
	
	public function exist(string $query, array $values = []): bool
	{
		$query = $this->pdo->prepare($query);
		$query->execute($values);
		$exist = $query->fetch(PDO::FETCH_ASSOC);
		
		return ($exist != false);
	}
	
	public function existByColumnValue(string $table, string $column, string|int|float|bool $value): bool
	{
		return $this->exist('
			SELECT * 
			FROM `' . $table . '` 
			WHERE `' . $column . '` = :value
		', [
			':value'	=> $value,
		]);
	}
}
