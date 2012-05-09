<?php
/**
 * Load next page of a listing through ajax
 *
 * @package ElggInfiniteScroll
 */
?>

elgg.provide('elgg.infinite_scroll');

elgg.infinite_scroll.load_next = function(event, direction) {
	$(this).waypoint('remove');
	$list = $(this).parent();
	$list.toggleClass('infinite-scroll-ajax-loading', true);
	
	$params = elgg.parse_str(elgg.parse_url(location.href).query);
	$params = $.extend($params, {
		path: elgg.parse_url(location.href).path,
		items_type: $list.hasClass('elgg-list-entity') ? 'entity' :
					$list.hasClass('elgg-list-river') ? 'river' :
					$list.hasClass('elgg-list-annotation') ? 'annotation' : false,
		offset: $list.children().length + (parseInt($params.offset) || 0)
	});
	
	url = "/ajax/view/infinite_scroll/list?" + $.param($params);
	elgg.get(url, function(data) {
		$list.toggleClass('infinite-scroll-ajax-loading', false);
		if (data && $(data).children().length == $list.data('infinite-scroll-limit')) {
			$last = $list.find(" > li:last");
			$list.append($(data).children());
			$last.waypoint(elgg.infinite_scroll.load_next, {
				offset: '100%',
			});
			list_bottom = false;
		} else if (data) {
			$list.append($(data).children());
			list_bottom = true;
		}
		if (!data || list_bottom) {
			$list.append('<li class="infinite-scroll-bottom">'+elgg.echo('infinite_scroll:list_bottom')+'</li>');
		}
	});
}

elgg.infinite_scroll.init = function() {
	
	// Select all paginated .elgg-list near a .elgg-pagination and not into widget
	$('.elgg-pagination').siblings('.elgg-list').filter(':not(.elgg-module *)')
	
	// Hide pagination
	.siblings('.elgg-pagination').hide().end()
	
	// Set limit as HTML5 data attribute
	.each(function(){
		$(this).data('infinite-scroll-limit', $(this).children().length);
	})
	
	// When first list item is reached, begin loading the next page via ajax
	.find(' > li:first').waypoint(elgg.infinite_scroll.load_next, {
		offset: '100%',
	});
};

elgg.register_hook_handler('init', 'system', elgg.infinite_scroll.init);
