<?php
/*
Plugin Name: Debug Bar Post Meta
Plugin URI: http://github.com/tddewey/
Description: Very simple extention to the debug bar to get a list of post meta for the current post
Author: Taylor Dewey
Version: 0.1
Author URI: http://github.com/tddewey

Plugin structure taken from Debug Bar Cron, a fine plugin by Zack Tollman and Helen Hou-Sandi
*/

/**
 * Adds panel, as defined in the included class, to Debug Bar.
 *
 * @param $panels array
 * @return array
 */
function tdd_dbpm_debug_bar_panels( $panels ) {
	if ( ! class_exists( 'TDD_Debug_Bar_Post_Meta' ) ) {
		include ( 'class-debug-bar-post-meta.php' );
		$panels[] = new TDD_Debug_Bar_Post_Meta();
	}
	return $panels;
}
add_filter( 'debug_bar_panels', 'tdd_dbpm_debug_bar_panels' );