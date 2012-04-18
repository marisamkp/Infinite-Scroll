<?php
/**
 * Load next page of a listing through ajax
 *
 * @package ElggInfiniteScroll
 */
?>

elgg.provide('elgg.infinite_scroll');

elgg.infinite_scroll.load_next = function(event, direction) {
	$list = $(this).parent();
	$(this).waypoint('remove');
	
	$params = elgg.parse_str(elgg.parse_url(location.href).query);
	$params = $.extend($params, {
		path: elgg.parse_url(location.href).path,
		list_type: $list.hasClass('elgg-list-entity') ? 'entity' :
					$list.hasClass('elgg-list-river') ? 'river' :
					$list.hasClass('elgg-list-annotation') ? 'annotation' : false,
		offset: $list.children().length + (parseInt($params.offset) || 0),
	});
	
	url = "/ajax/view/infinite_scroll/list?" + $.param($params);
	elgg.get(url, function(data) {
		if (data) {
			$list.find(" > li:last").waypoint(elgg.infinite_scroll.load_next, {
				offset: '100%',
			});
			$list.append($(data).children());
		}
	});
}

elgg.infinite_scroll.init = function() {	
	//$('.elgg-pagination').hide();
	$('.elgg-list')
	.find(' > li:first').waypoint(elgg.infinite_scroll.load_next, {
		offset: '100%',
	});
};

elgg.register_hook_handler('init', 'system', elgg.infinite_scroll.init);
