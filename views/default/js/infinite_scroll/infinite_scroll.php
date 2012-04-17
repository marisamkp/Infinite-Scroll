<?php
/**
 * Load next page of a listing through ajax
 *
 * @package ElggInfiniteScroll
 */
?>

elgg.provide('elgg.infinite_scroll');

elgg.infinite_scroll.init = function() {	
	$('.elgg-pagination').hide();
	
	opts = {
		offset: '100%',
	};
	$('.elgg-list > li:last').waypoint(function(event, direction) {
		$(this).waypoint('remove');
		alert("...");
	}, opts);
};

elgg.register_hook_handler('init', 'system', elgg.infinite_scroll.init);
