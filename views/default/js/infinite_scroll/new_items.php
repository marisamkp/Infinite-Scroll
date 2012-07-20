<?php
/**
 * Load new items of a list through ajax when a button clicked
 *
 * @package ElggInfiniteScroll
 */
?>

elgg.provide('elgg.infinite_scroll.new_items');

elgg.infinite_scroll.new_items.prepend = function(data) {
	var $list = $('.elgg-pagination').siblings('.elgg-list, .elgg-gallery').filter(':not(.elgg-module *)');
	if (data) {
		var n = $list.children(":hidden").length + $(data).children().length;
		$list.prepend($(data).children().hide());
		$('.elgg-infinite-scroll-top').find('.elgg-button').text(
			elgg.echo('infinite_scroll:new_items', [n])
		).end().show();
	}
	$list.trigger('prepend', data);
};

elgg.infinite_scroll.new_items.check = function() {
	// Select all paginated .elgg-list or .elgg-gallery witch aren't into widgets
	var $list = $('.elgg-pagination').siblings('.elgg-list, .elgg-gallery').filter(':not(.elgg-module *)');
	
	elgg.infinite_scroll.load($list, 0, function(data){
		elgg.infinite_scroll.new_items.prepend(data);
		setTimeout(elgg.infinite_scroll.new_items.check, 30000);
	});
};

elgg.infinite_scroll.new_items.init = function() {

	// Select all paginated .elgg-list or .elgg-gallery witch aren't into widgets
	$list = $('.elgg-pagination').siblings('.elgg-list, .elgg-gallery').filter(':not(.elgg-module *)')
	
	// Add new items button at the begining of the list
	.before(
		$('<div class="elgg-infinite-scroll-top"></div>')
		.append(
			$('<?php
				echo elgg_view('output/url', array(
					'text' => '',
					'href' => '',
					'class' => 'elgg-button',
				)); 
			?>').click(function(){
				$list.children().slideDown({
					duration: 700,
					easing: 'easeInCubic',
				});
				$(this).parent().hide();
				return false;
			})
		).hide()
	);
	// Check for new items each 30s.
	setTimeout(elgg.infinite_scroll.new_items.check, 0);
};

elgg.register_hook_handler('init', 'system', elgg.infinite_scroll.new_items.init);
