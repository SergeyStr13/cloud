<?php

class Dump {
	
	private static $output = '';

	private static $enabledSql = false;
	
	public static function log(...$args): void {
		ob_start();
		foreach ($args as $arg) {
			echo '<div class="debug-row channel-console">';
				echo '<pre>';
					echo '<span class="channel-label">Console:</span> '.self::dumpValue($arg);
				echo '</pre>';
			echo '</div>';
		}
		self::$output .= ob_get_clean();
	}
	
	public static function logWarning(string $message, ...$args): void {
		ob_start();
		echo '<div class="debug-row channel-warning">';
			echo '<pre>';
				echo '<span class="channel-label">Warning:</span> '.$message;
			echo '</pre>';
		echo '</div>';
		foreach ($args as $arg) {
			echo '<div class="debug-row channel-warning">';
				echo '<pre>';
					echo self::dumpValue($arg);
				echo '</pre>';
			echo '</div>';
		}
		self::$output .= ob_get_clean();
	}

	public static function dumpValue($value): string {
		if (is_string($value)) {
			$dump = '"'.htmlentities($value).'"';
		} else if ($value === null) {
			$dump = 'null';
		} else {
			ob_start();
			var_dump($value);
			$dump = preg_replace('#\[([^\]]*)\]=>\s*#', '$1: ', ob_get_clean());
			$dump = preg_replace_callback('#\n\s{2,}#', function($matches) {
				return str_replace('  ', '    ', $matches[0]);
			}, $dump);
			$dump = htmlentities($dump);
		}
		return $dump;
	}

	public static function enableSql(bool $enabled = true) {
		self::$enabledSql = $enabled;
	}

	public static function logSql(string $query, array $params, array $info = []): void {
		if (!self::$enabledSql) {
			return;
		}
		$query = str_replace([
			' left join',
			' where',
			' and',
			' or',
			'(',
			')',
		], [
			" \n    ".'left join',
			" \n    ".'where',
			" \n        ".'and',
			" \n        ".'or',
			"(\n        ",
			 "\n    ".')',
		], $query);
		ob_start();
		echo '<div class="debug-row channel-sql">';
			echo '<pre><span class="channel-label">Sql:</span> '.$query."\n".'</pre>';
			echo '<pre>';
				echo '<span class="label">params:</span> '.str_pad(
					'{'.
						implode(', ', array_map( function($param, $value) {
							/* if ($value instanceof DateTime) {
								ob_start();
								var_dump($value);
								$value = ob_get_clean();
							} */
							return "$param: '$value'";
						}, array_keys($params), array_values($params))).
					'}', 40
				);
				foreach ($info as $param => $value) {
					echo "\t".'<span class="label">'.$param.':</span> '.$value;
				}
			echo '</pre>';
		echo '</div>';
		self::$output .= ob_get_clean();
	}
	
	public static function getOutput(): string {
		return self::$output;
	}
	
}

\Illuminate\Database\Eloquent\Collection::macro('maxBy', function ($callback) {
    $callback = $this->valueRetriever($callback);

    return $this->reduce(function ($result, $item) use ($callback) {
        if ($result === null) {
            return $item;
        }
        return $callback($item) > $callback($result) ? $item : $result;
    });
});

\Illuminate\Database\Eloquent\Collection::macro('minBy', function ($callback) {
    $callback = $this->valueRetriever($callback);

    return $this->reduce(function ($result, $item) use ($callback) {
        if ($result === null) {
            return $item;
        }
        return $callback($item) < $callback($result) ? $item : $result;
    });
});

function dump(...$args): void {
	Dump::log(...$args);
}

function outputDump(): void {
	echo Dump::getOutput();
	exit;
}

function warning(...$args): void {
	Dump::logWarning(...$args);
}

function hideErrorsRecoverable() {
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
}

// making \DB available, but prevent interfering with src/laravel-ide-helper
class_alias( 'Illuminate\Database\Capsule\Manager', (string) 'DB');


class Auth {

	/**
	 * @return \Pandora3\Plugins\Authorisation\AuthorisableInterface
	 */
	public static function getUser() {
		return \App\App::getInstance()->auth->getUser();
	}
	
}
