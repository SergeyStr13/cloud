<?php

return [
	'/users/*' => \App\Plugins\Users\Controllers\UserController::class,
	'/auth/*' => \App\Plugins\Users\Controllers\AuthorisationController::class,
];