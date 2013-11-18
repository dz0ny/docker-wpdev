<?php
/*
Plugin Name: Debug Bar Constants
Plugin URI: http://wordpress.org/extend/plugins/debug-bar-constants/
Description: Debug Bar Constants adds new panels to Debug Bar that display all the defined constants for the current request. Requires "Debug Bar" plugin.
Version: 1.2.1.1
Author: Juliette Reinders Folmer
Author URI: http://www.adviesenzo.nl/
Text Domain: debug-bar-constants
Domain Path: /languages/

Copyright 2013 Juliette Reinders Folmer
*/
/*
GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Avoid direct calls to this file
if ( !function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Show admin notice & de-activate itself if debug-bar plugin not active
 */
add_action( 'admin_init', 'dbc_has_parent_plugin' );

if ( !function_exists( 'dbc_has_parent_plugin' ) && !function_exists( 'dbc_missing_parent_plugin' ) ) {
	/**
	 * Check for parent plugin
	 */
	function dbc_has_parent_plugin() {
		if ( is_admin() && ( !class_exists( 'Debug_Bar' ) && current_user_can( 'activate_plugins' ) ) ) {
			/**
			 * @todo debug - will throw notice of 'function not found' as this plugin will already
			 * be deactivated when the admin notice is called
			 */
			add_action( 'admin_notices', 'dbc_missing_parent_plugin' );

			deactivate_plugins( plugin_basename( __FILE__ ) );
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}

	/**
	 * Show admin notice
	 */
	function dbc_missing_parent_plugin() {
		$activate = admin_url( 'plugins.php#debug-bar' );
		$string   = '<div class="error"><p>' . sprintf( __( 'Debug Bar must be activated to use the Debug Bar Constants Plugin. <a href="%s">Visit your plugins page to activate</a>.', 'debug-bar-constants' ), $activate ) . '</p></div>';
		echo $string;
	}
}



if ( !function_exists( 'debug_bar_constants_panels' ) ) {
	// Low priority, no need for it to be high up in the list
	add_filter( 'debug_bar_panels', 'debug_bar_constants_panels', 12 );

	/**
	 * Add the Debug Bar Constant panels to the Debug Bar
	 *
	 * @param   array   $panels     Existing debug bar panels
	 * @return  array
	 */
	function debug_bar_constants_panels( $panels ) {
		if ( ( !class_exists( 'Debug_Bar_WP_Constants' ) && !class_exists( 'Debug_Bar_WP_Class_Constants' ) ) && !class_exists( 'Debug_Bar_PHP_Constants' ) ) {
			require_once 'class-debug-bar-constants.php';
		}
		$panels[] = new Debug_Bar_WP_Constants();
		$panels[] = new Debug_Bar_WP_Class_Constants();
		$panels[] = new Debug_Bar_PHP_Constants();
		return $panels;
	}
}