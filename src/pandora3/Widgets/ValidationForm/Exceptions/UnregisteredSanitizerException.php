<?php
namespace Pandora3\Widgets\ValidationForm\Exceptions;

use RuntimeException;
use Pandora3\Core\Interfaces\Exception\CoreException;

class UnregisteredSanitizerException extends RuntimeException implements CoreException { }