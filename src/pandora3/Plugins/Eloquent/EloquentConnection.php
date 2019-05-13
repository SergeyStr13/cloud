<?php
namespace Pandora3\Plugins\Eloquent;

use Illuminate\Database\Capsule\Manager as EloquentManager;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use Illuminate\Database\DatabaseManager;
use Pandora3\Core\Interfaces\DatabaseConnectionInterface;

class EloquentConnection implements DatabaseConnectionInterface {
	
	/** @var array $params */
	protected $params;

	/** @var EloquentManager $eloquent */
	protected $eloquent;

	/** @var DatabaseManager $database */
	protected $database;
	
	/* *
	 * @return EloquentManager
	 */
	/* protected function getManager(): EloquentManager {
		if (self::$eloquent === null) {
			self::$eloquent = new EloquentManager();
		}
		return self::$eloquent;
	} */
	
	/**
	 * @param array $params
	 */
	public function __construct(array $params) {
		$this->params = $params;
		// $eloquent = self::getManager();
		$connectionName = $params['connectionName'] ?? 'default';
		$this->eloquent = new EloquentManager();
		$this->eloquent->addConnection([
			'driver' => $params['driver'],
			'host' => $params['host'],
			'database' => $params['database'],
			'username' => $params['username'],
			'password' => $params['password'],
			'charset' => $params['encoding'] ?? 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => $params['prefix'] ?? '',
		], $connectionName);
		if ($params['global'] ?? false) {
			$this->setAsGlobal();
		}
		$this->connect();
	}

	/**
	 * @return SchemaBuilder
	 */
	public function getSchemaBuilder(): SchemaBuilder {
		return $this->database->getSchemaBuilder();
	}
	
	/**
	 * @return EloquentManager
	 */
	public function getManager(): EloquentManager {
		return $this->eloquent;
	}

	public function setAsGlobal(): void {
		$this->eloquent->setAsGlobal();
	}
	
	public function connect(): void {
		$this->eloquent->bootEloquent();
		$this->database = $this->eloquent->getDatabaseManager();
	}
	
	public function close(): void {
		$this->database->disconnect();
	}
	
}