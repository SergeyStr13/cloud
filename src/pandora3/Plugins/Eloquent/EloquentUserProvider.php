<?php
namespace Pandora3\Plugins\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Pandora3\Plugins\Authorisation\AuthorisableInterface;
use Pandora3\Plugins\Authorisation\UserProviderInterface;

class EloquentUserProvider implements UserProviderInterface {

	/** @var string $userModel */
	protected $userModel;
	
	/** @var mixed|null $guestUser */
	protected $guestUser;
	
	/**
	 * @param string $userModel
	 * @param mixed $guestUser
	 */
	public function __construct(string $userModel, $guestUser = null) {
		$this->userModel = $userModel;
		$this->guestUser = $guestUser;
	}
	
	/**
	 * @return mixed|null
	 */
	public function getGuestUser() {
		return $this->guestUser;
	}

	/**
	 * @param mixed $id
	 * @return AuthorisableInterface|null
	 */
	public function getUserById($id): ?AuthorisableInterface {
		/** @var Model $userModel */
		$userModel = $this->userModel;

		/** @var AuthorisableInterface $user */
		$user = $userModel::find($id);
		return $user;
	}

	/**
	 * @param string $login
	 * @return AuthorisableInterface|null
	 */
	public function getUserByLogin(string $login): ?AuthorisableInterface {
		/** @var Model $userModel */
		$userModel = $this->userModel;

		/** @var AuthorisableInterface $user */
		$user = $userModel::where(['login' => $login])->first();
		return $user;
	}

}