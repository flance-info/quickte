<?php

add_filter(
	'masterstudy_lms_plugin_addons',
	function ( $addons ) {
		return array_merge(
			$addons,
			array(
				new \MasterStudy\Lms\Pro\addons\settings\Settings(),
			)
		);
	}
);