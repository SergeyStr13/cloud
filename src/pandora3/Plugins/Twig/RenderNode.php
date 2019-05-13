<?php
namespace Pandora3\Plugins\Twig;

use Twig_Node_Expression as NodeExpression;
use Twig_Node as Node;
use Twig_Compiler as Compiler;

class RenderNode extends Node {

	public function __construct(NodeExpression $value, $line, $tag = null) {
		parent::__construct(['value' => $value], [], $line, $tag);
	}

	public function compile(Compiler $compiler) {
		$compiler
			->addDebugInfo($this)
			->write('echo ')
			->subcompile($this->getNode('value'))
			->raw(";\n");
	}

}