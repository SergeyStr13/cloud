<?php
namespace Pandora3\Widgets\ValidationForm\Exceptions;

use RuntimeException;
use Pandora3\Core\Application\Exception\CoreException;

class UnregisteredRuleException extends RuntimeException implements CoreException { }