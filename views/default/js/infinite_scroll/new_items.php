<?php
/**
 * Load new items of a list through ajax when a button clicked
 *
 * @package ElggInfiniteScroll
 */
?>

elgg.provide('elgg.infinite_scroll.new_items');

elgg.infinite_scroll.new_items.append = function() {

};

elgg.infinite_scroll.new_items.check = function() {
	
};

elgg.infinite_scroll.new_items.init = function() {
	// Check for new items each 30s.
	setTimeout(elgg.infinite_scroll.new_items.check, 30000);
};

elgg.register_hook_handler('init', 'system', elgg.infinite_scroll.new_items.init);
