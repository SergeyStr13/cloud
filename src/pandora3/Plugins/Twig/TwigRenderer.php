<?php
namespace Pandora3\Plugins\Twig;

use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigRenderer {
	
	/** @var Environment $twig */
	protected $twig;
	
	/**
	 * @param string $path
	 * @param array $options
	 */
	public function __construct(string $path, array $options = []) {
		$this->twig = new Environment(new FilesystemLoader(APP_PATH.'/Views'), $options);
		$this->twig->addTokenParser(new RenderTokenParser());
		$this->twig->addExtension(new JsFilterExtension());
	}

	public function getEnvironment(): Environment {
		return $this->twig;
	}

	/**
	 * @param ExtensionInterface[] $extensions
	 */
	public function addExtensions(array $extensions): void {
		foreach ($extensions as $extension) {
			$this->twig->addExtension($extension);
		}
	}

	public function addFunctions(array $functions): void {
		foreach ($functions as $name => $function) {
			$this->twig->addFunction(new TwigFunction($name, $function));
		}
	}
	
	/**
	 * @param string $viewPath
	 * @param array $context
	 * @return string
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function render(string $viewPath, array $context = []): string {
		return $this->twig->render($viewPath, $context);
	}
	
}