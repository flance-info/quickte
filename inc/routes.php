<?php

/** @var \MasterStudy\Lms\Routing\Router $router */

$router->get(
	'/courses/{course_id}/settingsm',
	\MasterStudy\Lms\Http\Controllers\Course\GetSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\GetSettings::class
);


