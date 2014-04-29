<?php



# Documentation
Router::connect('/gallery/docs',
	array(
		'controller' => 'gallery',
		'action' => 'docs',
		'plugin' => 'gallery'
	));

Router::connect('/gallery/install/configure',
	array(
	'controller' => 'install',
	'action' => 'configure',
	'plugin' => 'gallery'
));

# Standalone Gallery
Router::connect('/gallery/upload',
	array(
		'controller' => 'albums',
		'action' => 'upload',
		'plugin' => 'gallery'
	));

Router::connect('/gallery/upload/:gallery_id',
	array(
		'controller' => 'albums',
		'action' => 'upload',
		'plugin' => 'gallery'
	),
	array(
		'named' => array(
			'gallery_id' => array('gallery_id')
		),
		'pass' => array('gallery_id')
	)
);

# Model attached Gallery
Router::connect(
	'/gallery/upload/:model/:model_id',
	array(
		'controller' => 'albums',
		'action' => 'upload',
		'plugin' => 'gallery'
	),
	array(
		'pass' => array('model', 'model_id')
	)
);
