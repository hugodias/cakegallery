<?php
$config = array(
	'App' => array(
		'theme' => 'superhero'
	),
	'File' => array(
		'max_file_size' => '20', // In MegaBytes (mb)
		'allowed_extensions' => array('jpg','png','jpeg','gif')
	),
  'Pictures' => array(
    'keep_original' => true, // Set FALSE if you want to delete the original file to keep your storage low
    'jpg_quality' => '80', // 80% quality
	  'resize_to' => array(0, 533, false),
    // List of additional files generated after upload, like thumbnails, banners, etc
    'styles' => array(
      'small' => array(50, 50, true), # 50x50 Cropped
      'medium' => array(200, 200, true), # 200x200 Cropped
      'large' => array(0, 533, false) # 533 pixels height (and unlimited width)
      )
    )
);
Configure::write('GalleryOptions', $config);


