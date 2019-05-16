<?php
namespace Pandora3\Core\Container\Exception;

use RuntimeException;
use Pandora3\Core\Interfaces\Exception\CoreException;

/**
 * Class ContainerException
 * @package Pandora3\Core\Container\Exception
 */
abstract class ContainerException extends RuntimeException implements CoreException {

}