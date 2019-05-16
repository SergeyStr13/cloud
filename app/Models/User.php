<?php
namespace App\Models;

use App\Models\Enum\Group;
use Illuminate\Database\Eloquent\Model;
use Pandora3\Libs\Application\Application;
use Pandora3\Plugins\Authorisation\AuthorisableInterface;

/**
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $createTime
 * @property string $updateTime
 * @property int $group
 *
 * @property-read string $groupTitle
 */
class User extends Model implements AuthorisableInterface {

	protected $table = 'user';

	protected $guarded = [];

	const CREATED_AT = 'createTime';
	const UPDATED_AT = 'updateTime';


	public function getGroupTitleAttribute(): string {
		return Group::getTitle($this->group);
	}


	public function getAuthorisationId() {
		return $this->id;
	}

	/**
	 * @param array|int $groups
	 * @return bool
	 */
	public function hasGroup($groups): bool {
		if (!is_array($groups)) {
			$groups = [$groups];
		}
		return in_array($this->group, $groups);
	}


	/**
	 * @param string $password
	 * @param string $secret
	 * @return string
	 */
	public static function hashPassword(string $password, string $secret = ''): string {
		if (!$secret) {
			$secret = Application::getInstance()->getSecret();
		}
		return hash_hmac('sha512', $password.$secret, $secret);
	}

	public function checkPassword(string $password): bool {
		return $this->password === self::hashPassword($password);
	}

}
