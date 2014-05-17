<?php

class GalleryPictureFixture extends CakeTestFixture
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'path' => array('type' => 'string', 'length' => 255, 'null' => false),
        'size' => array('type' => 'integer', 'length' => 11, 'null' => false),
        'album_id' => array('type' => 'integer', 'length' => 11, 'null' => false),
        'main_id' => array('type' => 'integer', 'length' => 1, 'null' => true, 'default' => null),
        'style' => array('type' => 'string', 'length' => 255, 'null' => false, 'default' => 'full'),
        'order' => array('type' => 'integer', 'length' => 11, 'null' => false, 'default' => 9999999),
        'created' => 'datetime',
        'updated' => 'datetime'
    );

    public function init()
    {
        $this->records = array(
            array(
                'id' => 1,
                'path' => '/Applications/MAMP/htdocs/cakephp/app/webroot/files/gallery/1/1.jpg',
                'size' => 7235623734,
                'album_id' => 1,
                'main_id' => null,
                'style' => 'full',
                'order' => 9999999,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ),
            array(
                'id' => 2,
                'path' => '/Applications/MAMP/htdocs/cakephp/app/webroot/files/gallery/1/medium-1.jpg',
                'size' => 7235623734,
                'album_id' => 1,
                'main_id' => 1,
                'style' => 'medium',
                'order' => 9999999,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            )
        );
        parent::init();
    }
}