<?php
namespace MasterStudy\Lms\Pro\addons\settings;

use MasterStudy\Lms\Plugin\Addon;


class Settings implements Addon {

	/**
	 * @return string
	 */
	public function get_name(): string {
		return 'GET Settings';
	}

	/**
	 *
	 * @param \MasterStudy\Lms\Plugin $plugin
	 */
	public function register( \MasterStudy\Lms\Plugin $plugin ): void {
		$plugin->get_router()->load_routes( __DIR__ . '/routes.php' );
	}

}