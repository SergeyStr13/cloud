<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $filename
 * @property int $directoryId
 * @property int $userId
 * @property string $createTime
 * @property string $updateTime
 * @property int $downloads
 */
class StorageFile extends Model {

	protected $table = 'storageFile';

	protected $guarded = [];

	const CREATED_AT = 'createTime';
	const UPDATED_AT = 'updateTime';

}