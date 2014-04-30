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
	  # Resize original image. If you dont want to resize it, you should set a empty array, E.G: 'resize_to' => array()
	  # Default configuration will resize the image to 1024 pixels height (and unlimited width)
	  'resize_to' => array(0, 1024, false),

	  # Set to TRUE if you want to convert all png files to JPG (reduce significantly image size)
	  'png2jpg' => true,

	  # Set the JPG quality on each resize.
	  # The recommended value is 80 (80% quality)
    'jpg_quality' => '80',


    # List of additional files generated after upload, like thumbnails, banners, etc
    'styles' => array(
      'small' => array(50, 50, true), # 50x50 Cropped
      'medium' => array(255, 170, true), # 255#170 Cropped
      'large' => array(0, 533, false) # 533 pixels height (and unlimited width)
      )
    )
);
Configure::write('GalleryOptions', $config);


