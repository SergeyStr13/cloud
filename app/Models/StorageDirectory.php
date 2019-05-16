<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property int|null $parentId
 * @property int $userId
 */
class StorageDirectory extends Model {

	protected $table = 'storageDirectory';

	protected $guarded = [];

	public $timestamps = false;

}