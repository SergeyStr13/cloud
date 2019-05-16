<?php
namespace App;

use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Events\Dispatcher;
use Pandora3\Libs\Application\Application;
use Pandora3\Core\Container\Container;
use Pandora3\Core\Http\Response;
use Pandora3\Core\Interfaces\DatabaseConnectionInterface;
use Pandora3\Plugins\Authorisation\UserProviderInterface;
use Pandora3\Plugins\Eloquent\EloquentConnection;
use Pandora3\Plugins\Eloquent\EloquentUserProvider;

class App extends Application {

	/** @var array $routes */
	private $routes = [];

	/**
	 * @param string $path
	 */
	protected function addRoutes(string $path): void {
		$routes = include($path.'/routes.php') ?? [];
		$this->routes = array_replace($this->routes, $routes);
	}

	/**
	 * @param array $pluginNames
	 */
	protected function addPluginRoutes(array $pluginNames): void {
		foreach ($pluginNames as $pluginName) {
			$this->addRoutes("{$this->path}/Plugins/{$pluginName}");
		}
	}

	protected function getUserModel(): string {
		return User::class;
	}

	/**
	 * @param Container $container
	 */
	protected function dependencies(Container $container): void {
		parent::dependencies($container);

		$container->setShared(DatabaseConnectionInterface::class, EloquentConnection::class);
		$container->setShared(UserProviderInterface::class, EloquentUserProvider::class);

		$container->set(EloquentUserProvider::class, function() {
			return new EloquentUserProvider($this->getUserModel());
		});

		if ($this->config->has('database')) {
			$connection = new EloquentConnection( array_replace(
				$this->config->get('database'), ['global' => true]
			));

			$eloquent = $connection->getManager();
			$dispatcher = new Dispatcher();
			$dispatcher->listen(QueryExecuted::class, function(QueryExecuted $query) {
				\Dump::logSql($query->sql, $query->bindings, ['time' => $query->time.'ms', 'connection' => $query->connectionName]);
			});
			$eloquent->setEventDispatcher($dispatcher);

			$container->setShared(EloquentConnection::class, function() use ($connection) {
				return $connection;
			});
		}

	}


	/**
	 * Gets the application routes
	 * @return array
	 */
	protected function getRoutes(): array {
		$this->addPluginRoutes([
			'Users',
		]);
		return array_replace(['/*' => function() {
			return new Response('', [
				'location' => '/users'
			]);
		}], $this->routes);
	}

}