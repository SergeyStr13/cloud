<?php
namespace Pandora3\Plugins\Authorisation;

interface UserProviderInterface {

	/**
	 * @param mixed|null $id
	 * @return AuthorisableInterface|null
	 */
	function getUserById($id);

	/**
	 * @param string $login
	 * @return AuthorisableInterface|null
	 */
	function getUserByLogin(string $login);

	/**
	 * @return mixed|null
	 */
	function getGuestUser();

}