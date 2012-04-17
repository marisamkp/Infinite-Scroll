<?php
/**
 * Elgg Infinite Scroll
 *
 * @package ElggInfiniteScroll
 */

elgg_register_event_handler('init', 'system', 'infinite_scroll_init');

/**
 * Initialize the infinite scroll plugin.
 *
 */
function infinite_scroll_init() {

	// Extend the main css view
	elgg_extend_view('css/elgg', 'infinite_scroll/css');

	// Register javascript needed for infinite scrolling
	$js_url = 'mod/infinite_scroll/vendors/jquery-waypoints/jquery.waypoints.min.js';
	elgg_register_js('jquery-waypoints', $js_url);
}
