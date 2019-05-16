<?php

return [
	'/users/*' => \App\Plugins\Users\Controllers\UserController::class,
	'/auth/*' => \App\Plugins\Users\Controllers\AuthorisationController::class,
	'/storage/*' => \App\Plugins\Storage\Controllers\StorageController::class,
	'/storage/directory' => \App\Plugins\Storage\Controllers\StorageController::class,
];