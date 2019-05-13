<?php
namespace App\Plugins\Users\Forms;

use Pandora3\Widgets\ValidationForm\ValidationForm;

class SignInForm extends ValidationForm {

	protected function getFields(): array {
		return [
			'login' =>		['type' => 'input',     'label' => 'Логин'],
			'password' =>	['type' => 'password',  'label' => 'Пароль'],
		];
	}

	protected function getButtons(): array {
		return [
			'signIn' => [
				'type' => 'submit',             'title' => 'Войти',
				'class' => 'button-primary',    'icon' => '<i class="mdi mdi-login-variant"></i>',
			]
		];
	}

	protected function rules(): array {
		return [
			'login' => 'required',
			'password' => 'required',
		];
	}

}