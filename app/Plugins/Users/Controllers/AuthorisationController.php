<?php
namespace App\Plugins\Users\Controllers;

use App\Plugins\Users\Forms\SignInForm;
use Pandora3\Core\Controller\Controller;
use Pandora3\Core\Interfaces\ResponseInterface;
use Pandora3\Plugins\Authorisation\Authorisation;
use Pandora3\Plugins\Authorisation\Exception\AuthorisationFailedException;

class AuthorisationController extends Controller {

	/** @var Authorisation $auth */
	protected $auth;

	public function __construct(Authorisation $authorisation) {
		$this->auth = $authorisation;
	}

	public function getRoutes(): array {
		return [
			'/' => 'signIn',
			'/sign-in' => 'signIn',
			'/sign-out' => 'signOut',
		];
	}

	protected function signIn(): ResponseInterface {
		$form = new SignInForm($this->request);

		if ($form->validate()) {
			try {
				$this->auth->authoriseByCredentials($form->get('login'), $form->get('password'));
				return $this->redirectUri('/');
			} catch (AuthorisationFailedException $ex) {
				$form->setMessage($ex->getMessage());
			}
		}

		return $this->render('SignInForm', compact('form'));
	}

	protected function signOut(): ResponseInterface {
		$this->auth->unAuthorise();
		return $this->redirectUri('/');
	}

}