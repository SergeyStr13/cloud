<?php
namespace Pandora3\Plugins\Twig;

use Twig_TokenParser as TokenParser;
use Twig_Node as Node;
use Twig_Token as Token;
use Twig_Error_Syntax as ErrorSyntax;

class RenderTokenParser extends TokenParser {
	
	/**
	 * @param Token $token
	 * @return Node
	 * @throws ErrorSyntax
	 */
	public function parse(Token $token) {
		$stream = $this->parser->getStream();

		$value = $this->parser->getExpressionParser()->parseExpression();
		$stream->expect(Token::BLOCK_END_TYPE);

		return new RenderNode($value, $token->getLine(), $this->getTag());
	}

	public function getTag() {
		return 'render';
	}
}