<?php
$config = array(
    'App' => array(
        /**
         * Choose what theme you want to use:
         * You can find all themes at Gallery/webroot/css/themes
         * Use the first name in the file as a parameter, eg: cosmo.min.css -> cosmo
         *
         */
        #'theme' => 'flatly',
        'theme' => 'flatly',
        /**
         * Use customized plugin interface
         *
         * Set false to disable
         */
        'interfaced' => true
    ),
    'File' => array(
        # Max size of a file (in megabytes (MB))
        'max_file_size' => '20',
        # What king pictures the user is allowed to upload?
        'allowed_extensions' => array('jpg', 'png', 'jpeg', 'gif')
    ),
    'Pictures' => array(
        # Resize original image. If you dont want to resize it, you should set a empty array, E.G: 'resize_to' => array()
        # Default configuration will resize the image to 1024 pixels height (and unlimited width)
        'resize_to' => array(0, 1024, false),
        # Set to TRUE if you want to convert all png files to JPG (reduce significantly image size)
        'png2jpg' => true,
        # Set the JPG quality on each resize.
        # The recommended value is 85 (85% quality)
        'jpg_quality' => 85,
        # List of additional files generated after upload, like thumbnails, banners, etc
        'styles' => array(
            'small' => array(50, 50, true), # 50x50 Cropped
            'medium' => array(255, 170, true), # 255#170 Cropped
            'large' => array(0, 533, false) # 533 pixels height (and unlimited width)
        )
    )
);
Configure::write('GalleryOptions', $config);

App::import(
    'Vendor',
    'Gallery.Zebra_Image',
    array('file' => 'ZebraImage.class.php')
);

