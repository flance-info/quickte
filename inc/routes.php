<?php

/** @var \MasterStudy\Lms\Routing\Router $router */
require_once 'GetSettingsController.php';
require_once 'GetSettings.php';
print_r($router);

$router->get(
	'/courses/{course_id}/settingsd',
	\MasterStudy\Lms\Http\Controllers\Course\GetSettingsControllerChild::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\GetSettingsChild::class
);


