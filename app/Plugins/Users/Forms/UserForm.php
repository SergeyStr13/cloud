<?php
namespace App\Plugins\Users\Forms;

use App\Models\User;
use Illuminate\Support\Collection as SupportCollection;
use Pandora3\Widgets\ValidationForm\ValidationForm;

class UserForm extends ValidationForm {

	/** @var array $userValues */
	public $userValues;

	protected function getFields(): array {
		return [
			'login' =>              ['type' => 'input',         'label' => 'Логин'],
			'password' =>           ['type' => 'passwordView',  'label' => 'Пароль'],
			'passwordConfirm' =>    ['type' => 'passwordView',  'label' => 'Подтверждение пароля'],
			'email' =>   			['type' => 'input',        	'label' => 'E-mail'],
			'group' =>   			['type' => 'select',        'label' => 'Группа'],
		];
	}

	/**
	 * @param User $user
	 * @return object
	 */
	protected function setModel($user) {
		return collect($user)
			->except('password')
			->toArray();
	}

	protected function rules(): array {
		return [
			'login' => 'required',
			'password' => $this->isUpdate ? [] : ['required'], // 'equal' => 'passwordConfirm'
			'email' => 'required',
			'group' => 'required',
		];
	}

	protected function afterValidate(array $values): array {
		$data = collect($values);
		
		$password = $data->get('password');
		$this->userValues = $data
			->only('login', 'group', 'email')
			->when($password, function(SupportCollection $c) use ($password) {
				return $c->merge(['password' => User::hashPassword($password)]);
			})
			->toArray();
			
		return $values;
	}

}