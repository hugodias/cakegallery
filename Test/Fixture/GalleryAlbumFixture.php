<?php

class GalleryAlbumFixture extends CakeTestFixture
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'title' => array('type' => 'string', 'length' => 255, 'null' => false),
        'default_name' => array('type' => 'string', 'length' => 255, 'null' => false),
        'path' => array('type' => 'string', 'length' => 255, 'null' => false),
        'model' => array('type' => 'string', 'length' => 255, 'null' => true),
        'model_id' => array('type' => 'integer', 'length' => 11, 'null' => true),
        'tags' => array('type' => 'string', 'length' => 255, 'null' => true),
        'status' => array('type' => 'string', 'length' => 255, 'null' => false),
        'created' => 'datetime',
        'updated' => 'datetime'
    );

    public function init()
    {
        $this->records = array(
            array(
                'id' => 1,
                'title' => 'First Album',
                'default_name' => 'First Article Body',
                'path' => '/1/',
                'model' => null,
                'model_id' => null,
                'tags' => null,
                'status' => 'published',
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ),
            array(
                'id' => 2,
                'title' => 'Second Album',
                'default_name' => 'First Article Body',
                'path' => '/1/',
                'model' => null,
                'model_id' => null,
                'tags' => null,
                'status' => 'draft',
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ),
            array(
                'id' => 3,
                'title' => 'Third Album',
                'default_name' => 'First Article Body',
                'path' => '/1/',
                'model' => null,
                'model_id' => null,
                'tags' => null,
                'status' => 'published',
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            )
        );
        parent::init();
    }
}