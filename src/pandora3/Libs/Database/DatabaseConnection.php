<?php
namespace Pandora3\Libs\Database;

use Pandora3\Libs\Database\Exception\ConnectionFailedException;
use PDO;
use PDOException;
use Pandora3\Core\Interfaces\DatabaseConnectionInterface;

/**
 * Class DatabaseConnection
 * @package Pandora3\Libs\Database
 */
class DatabaseConnection implements DatabaseConnectionInterface {

	/** @var array $params */
	protected $params;

	/** @var PDO $connection */
	protected $connection;

	/**
	 * @param array $params
	 */
	public function __construct(array $params) {
		$this->params = $params;
		$this->connect();
	}

	public function connect(): void {
		$driver = $this->params['driver'] ?? 'mysql';
		$host = $this->params['host'] ?? 'localhost';
		$database = $this->params['database'];
		$username = $this->params['username'];
		$password = $this->params['password'];
		$charset = $this->params['charset'] ?? 'utf8';
		// $collation = $this->params['collation'] ?? 'utf8_unicode_ci';
		// $prefix = $this->params['prefix'] ?? '';

		try {
			$this->connection = new PDO("$driver:host=$host;dbname=$database", $username, $password, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => "set names $charset",
			]);
		} catch (PDOException $ex) {
			throw new ConnectionFailedException('Failed to connect database', E_ERROR, $ex);
		}
	}

	public function close(): void {
		$this->connection = null;
	}

	public function getConnection(): PDO {
		return $this->connection;
	}

}