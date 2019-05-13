<?php
namespace Pandora3\Plugins\Authorisation;

use Pandora3\Core\Interfaces\SessionInterface;
use Pandora3\Plugins\Authorisation\Exception\AuthorisationFailedException;

class Authorisation {

	/** @var SessionInterface $session */
	protected $session;

	/** @var UserProviderInterface $userProvider */
	protected $userProvider;
	
	/** @var AuthorisableInterface $user */
	protected $user;
	
	/** @var int|null $userId */
	protected $userId;

	/**
	 * @param SessionInterface $session
	 * @param UserProviderInterface|null $userProvider
	 */
	public function __construct(SessionInterface $session, ?UserProviderInterface $userProvider = null) {
		$this->session = $session;
		$this->userProvider = $userProvider;
	}

	public function authorise(AuthorisableInterface $user): void {
		$this->user = $user;
		$this->userId = $user->getAuthorisationId();
		$this->session->set('userId', $this->userId);
	}
	
	public function unAuthorise(): void {
		$this->user = null;
		$this->userId = 0;
		$this->session->clear('userId');
	}

	/**
	 * @param string $login
	 * @param string $password
	 * @return AuthorisableInterface|null
	 */
	public function getByCredentials(string $login, string $password) {
		$user = $this->userProvider->getUserByLogin($login);
		if ($user && $user->checkPassword($password)) {
			return $user;
		}
		return null;
	}

	/**
	 * @param string $login
	 * @param string $password
	 * @return AuthorisableInterface|null
	 * @throws AuthorisationFailedException
	 */
	public function authoriseByCredentials(string $login, string $password) {
		$user = $this->getByCredentials($login, $password);
		if (!$user) {
			throw new AuthorisationFailedException('Неверный логин или пароль'); // Wrong login or password
		}
		$this->authorise($user);
		return $user;
	}

	/**
	 * @return AuthorisableInterface|null
	 */
	public function getUser() {
		if ($this->userProvider && $this->userId === null) {
			$this->userId = $this->session->get('userId') ?? 0;
			if ($this->userId) {
				$this->user = $this->userProvider->getUserById($this->userId) ?? $this->userProvider->getGuestUser();
			}
		}
		return $this->user;
	}

}