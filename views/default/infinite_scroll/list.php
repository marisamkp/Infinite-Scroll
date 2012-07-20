<?php

$path = explode('/', $vars['path']);
array_shift($path);

$list_type = get_input('list_type', 'list');
set_input('list_type', 'list');

ob_start();
elgg_set_viewtype('json');

// Check this when #4723 closed.
if (!$path[0]) {
	include(elgg_get_root_path().'index.php');
} else {
	page_handler(array_shift($path), implode('/', $path));
}

$json = json_decode(ob_get_clean());

switch(get_input('items_type')){
	case 'entity':
		foreach ($json as $child) foreach ($child as $grandchild) $json = $grandchild;
		
		/* Removing duplicates
		   This is unnecessary when #4504 is fixed. */
		if (version_compare(get_version(true), '1.8.7', '<')) {
			$buggy = $json;
			$json = array();
			$guids = array();
			foreach ($buggy as $item) {
				$guids[] = $item->guid;
			}
			$guids = array_unique($guids);
			foreach (array_keys($guids) as $i) {
				$json[$i] = $buggy[$i];
			}
		}
		break;
	case 'annotation': 
		foreach ($json as $child) {
			$json = $child;
		}
		$json = elgg_get_annotations(array(
			'items' => $json->guid,
			'offset' => get_input('offset'),
			'limit' => 25,
		));
		break;
	case 'river':
		$json = $json->activity;
		break;
}

if (!is_array($json)) {
	exit();
}

$items = array();
foreach($json as $item) {
	switch(get_input('items_type')) {
		case 'entity':
			$type_class = array(
				'site' => 'ElggSite',
				'user' => 'ElggUser',
				'group' => 'ElggGroup',
				'object' => 'ElggObject'
			);
			$items[] = new $type_class[$item->type]($item);
			break;
		case 'annotation': 
			$items = $json;
			break;
		case 'river':
			$items[] = new ElggRiverItem($item);
			break;
	}
}

header('Content-type: text/plain');

elgg_set_viewtype('default');
echo elgg_view("page/components/$list_type", array("items" => $items));
