<?php

# Standalone Gallery
Router::connect('/gallery/upload',
	array(
	'controller' => 'folders',
	'action' => 'upload',
	'plugin' => 'gallery'
));

# Model attached Gallery
Router::connect(
	'/gallery/upload/:model/:model_id',
	array(
		'controller' => 'folders',
		'action' => 'upload',
		'plugin' => 'gallery'
	),
	array(
		'pass' => array('model', 'model_id')
	)
);
