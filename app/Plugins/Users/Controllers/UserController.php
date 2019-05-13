<?php
namespace App\Plugins\Users\Controllers;

use App\Models\Enum\Group;
use App\Models\User;
use App\Plugins\Users\Forms\UserForm;
use Pandora3\Core\Interfaces\ResponseInterface;
use Pandora3\Core\Controller\Controller;

class UserController extends Controller {

	public function getRoutes(): array {
		return [
			'/' => 'users',
			'/add' => 'add',
			'/update' => 'update',
			'/delete' => 'delete',
		];
	}
	
	protected function users(): ResponseInterface {
		$users = User::all();

		return $this->render('Users', compact('users'));
	}

	protected function add(): ResponseInterface {
		$form = new UserForm($this->request);

		if ($form->validate()) {
			$user = new User($form->userValues);
			if (!$user->save()) {
				throw new \LogicException('User create failed');
			}
			return $this->redirectUri('/users');
		}

		$uriCancel = '/users';
		$options = [
			'groups' => array_replace(
				['' => '-- Не выбрана --'],
				Group::getOptions()
			)
		];

		return $this->render('Form', compact('form', 'uriCancel', 'options'));
	}
	
	protected function update(): ResponseInterface {
		$id = (int) $this->request->get('id');

		$user = User::findOrFail($id);
		$form = new UserForm($this->request, $user, $this->request->uri.'?id='.$id);

		if ($form->validate()) {
			if (!$user->update($form->userValues)) {
				throw new \LogicException('User update failed');
			}
			return $this->redirectUri('/users');
		}

		$uriCancel = '/users';
		$options = [
			'groups' => Group::getOptions()
		];

		return $this->render('Form', compact('form', 'uriCancel', 'options'));
	}

	protected function delete(): ResponseInterface {
		$id = (int) $this->request->get('id');

		$user = User::find($id);
		try {
			$user && $user->delete();
		} catch (\Exception $ex) {
			throw new \LogicException('User delete failed', E_WARNING, $ex);
		}

		return $this->redirectUri('/users');
	}

}