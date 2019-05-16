<?php
namespace Pandora3\Core\Interfaces;

interface RendererInterface {

	/**
	 * @param string $viewPath
	 * @param array $context
	 * @return string
	 * @throws \RuntimeException
	 */
	public function render(string $viewPath, array $context = []): string;

}