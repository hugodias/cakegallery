<?php
$config = array(
	'App' => array(
		'theme' => 'superhero'
	),
	'File' => array(
		'max_file_size' => '20', // In MegaBytes (mb)
		'allowed_extensions' => array('jpg','png','jpeg','gif')
	)
);
Configure::write('GalleryOptions', $config);


