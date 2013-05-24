<?php
/*
Plugin Name: SB Dynamic Components
Description: Inject dynamic content into static page
Author: Brandon Clark
Author URI: http://dtdevs.wordpress.com
Version: 1.0
License: GPL v2

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

define( 'SB_DC_URL', str_replace( ABSPATH, trailingslashit( site_url() ), dirname( __FILE__ ) ) . '/' );
define( 'SB_DC_PATH', dirname( __FILE__ ) );

/**
 * Dynamic Components Plugin
 */
class SB_DynamicComponents_Plugin {

	/**
	 * singleton instance
	 * @var SB_DynamicComponents_Plugin
	 */
	private static $instance = null;

	/**
	 * get singleton instance	 
	 * @return SB_DynamicComponents_Plugin
	 */
	public static function getInstance(){

		if ( self::$instance === null )
			self::$instance = new self();

		return self::$instance;

	}

	private function __construct(){

		require_once( dirname( __FILE__ ) . '/components.php' );
		require_once( dirname( __FILE__ ) . '/api.php' );

	}

}

$SB_DC_Plugin = SB_DynamicComponents_Plugin::getInstance();