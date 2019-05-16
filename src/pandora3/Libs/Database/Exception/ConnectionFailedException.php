<?php
namespace Pandora3\Libs\Database\Exception;

use RuntimeException;
use Pandora3\Core\Interfaces\Exception\CoreException;

/**
 * Class ConnectionFailedException
 * @package Pandora3\Libs\Database\Exception
 */
class ConnectionFailedException extends RuntimeException implements CoreException {

}