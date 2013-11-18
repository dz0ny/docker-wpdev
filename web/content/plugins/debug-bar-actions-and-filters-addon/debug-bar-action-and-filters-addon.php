<?php
	/*
	Plugin Name: Debug Bar Actions and Filters Addon
	Plugin URI: http://wordpress.org/extend/plugins/debug-bar-actions-and-filters-addon/
	Description: This plugin add two more tabs in the Debug Bar to display hooks(Actions and Filters) attached to the current request. Actions tab displays the actions hooked to current request. Filters tab displays the filter tags along with the functions attached to it with priority.
	Version: 0.11
	Author: Subharanjan
	Author Email: subharanjanmantri@gmail.com
	Author URI: http://www.subharanjan.in/
	License: GPLv2
	*/

	/**
	 * Function to hook with debug_bar_panels filter.
	 *
	 * @param array $panels list of all the panels in debug bar.
	 *
	 * @return array $panels modified panels list
	 */
	if ( ! function_exists( 'debug_bar_action_and_filters_addon_panel' ) ) {
		function debug_bar_action_and_filters_addon_panel( $panels ) {

			require_once( "class-debug-bar-action-and-filters-addon.php" );

			$wp_actions = new Debug_Bar_Actions_Addon_Panel();
			$wp_actions->set_tab( "Action Hooks", "debug_bar_action_and_filters_addon_display_actions" );
			$panels[] = $wp_actions;

			$wp_filters = new Debug_Bar_Filters_Addon_Panel();
			$wp_filters->set_tab( "Filter Hooks", "debug_bar_action_and_filters_addon_display_filters" );
			$panels[] = $wp_filters;

			return $panels;
		}
	}
	add_filter( 'debug_bar_panels', 'debug_bar_action_and_filters_addon_panel' );

	/**
	 * Function to display the Actions attached to current request.
	 * @return string $outout display output for the actions panel
	 */
	function debug_bar_action_and_filters_addon_display_actions() {
		global $wp_actions;

		$output = "";
		$output .= "<div class='hooks_listing_container'>";
		$output .= "<h2 style='width:100%;'>List of Action Hooks</h2><br />";
		$output .= "<ul style='list-style-type: square !important; padding-left: 20px !important;'>";
		foreach ( $wp_actions as $action_key => $action_val ) {
			$output .= "<li>" . $action_key . "</li>";
		}
		$output .= "<li><strong>Total Count: </strong>" . count( $wp_actions ) . "</li>";
		$output .= "</ul>";
		$output .= "</div>";

		return $output;
	}

	/**
	 * Function to display the Filters applied to current request.
	 * @return string $outout display output for the filters panel
	 */
	function debug_bar_action_and_filters_addon_display_filters() {
		global $wp_filter;

		$output = "";
		$output .= "<div class='hooks_listing_container'>";
		$output .= "<h2 style='width:100%;'>List of Filter Hooks (with functions)</h2><br />";
		$output .= "<ul style='list-style-type: square !important; padding-left: 20px !important;'>";
		foreach ( $wp_filter as $filter_key => $filter_val ) {

			$output .= "<li>";
			$output .= "<strong>" . $filter_key . "</strong><br />";
			$output .= "<ul style='list-style-type: square !important; padding-left: 20px !important;'>";
			ksort( $filter_val );
			foreach ( $filter_val as $priority => $functions ) {

				$output .= "<li>";
				$output .= "Priority: " . $priority . "<br />";

				$output .= "<ul style='list-style-type: square !important; padding-left: 20px !important;'>";
				foreach ( $functions as $single_function ) {

					if ( is_string( $single_function['function'] ) )
						$output .= "<li>" . $single_function['function'] . "</li>";
					elseif ( is_array( $single_function['function'] ) && is_string( $single_function['function'][0] ) )
						$output .= "<li>" . $single_function['function'][0] . " -> " . $single_function['function'][1] . "</li>";
					elseif ( is_array( $single_function['function'] ) && is_object( $single_function['function'][0] ) )
						$output .= "<li>(object) " . get_class( $single_function['function'][0] ) . " -> " . $single_function['function'][1] . "</li>";
					else
						$output .= "<li><pre>" . var_export( $single_function ) . "</pre></li>";

				}
				$output .= "</ul>";

				$output .= "</li>";

			}
			$output .= "</ul>";
			$output .= "</li>";

		}
		$output .= "</ul>";
		$output .= "</div>";

		return $output;
	}