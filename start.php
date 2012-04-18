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
	
	elgg_register_ajax_view('infinite_scroll/list');

	// Extend the main css view
	elgg_extend_view('css/elgg', 'infinite_scroll/css');

	// Register javascript needed for infinite scrolling
	$js_url = 'mod/infinite_scroll/vendors/jquery-waypoints/waypoints.min.js';
	elgg_register_js('jquery-waypoints', $js_url);
	
	// register the infinite scroll's JavaScript
	$infinite_scroll_js = elgg_get_simplecache_url('js', 'infinite_scroll/infinite_scroll');
	elgg_register_simplecache_view('js/infinite_scroll/infinite_scroll');
	elgg_register_js('elgg.infinite_scroll', $infinite_scroll_js);
	
	elgg_extend_view('navigation/pagination', 'infinite_scroll/load_js');
}
