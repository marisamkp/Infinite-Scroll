<?php

$path = explode('/', $vars['path']);
array_shift($path);

ob_start();
elgg_set_viewtype('json');
page_handler(array_shift($path), implode('/', $path));
elgg_set_viewtype('default');
$out = ob_get_contents();
ob_end_clean();

$json = json_decode($out);
switch(get_input('list_type')){
	case 'entity':
		foreach ($json as $child) foreach ($child as $grandchild) $json = $grandchild;
		break;
	case 'annotation': 
		//TODO
		break;
	case 'river':
		$json = $json->activity;
		break;
}



$items = array();
foreach($json as $item) {
	switch(get_input('list_type')) {
		case 'entity':
			$items[] = get_entity($item->guid);
			break;
		case 'annotation': 
			//TODO $items[] = new ElggAnnotation($item->id);
			break;
		case 'river':
			$items[] = new ElggRiverItem($item);
			break;
	}
}
header('Content-type: text/plain');
echo elgg_view('page/components/list', array("items" => $items));
