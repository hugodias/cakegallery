<?php
App::uses('Album', 'Gallery.Model');

class AlbumTest extends CakeTestCase {
    public $fixtures = array('plugin.gallery.album');
    public $Album;

    public function setUp() {
        parent::setUp();
        $this->Album = ClassRegistry::init('Gallery.Album');
    }

    public function testPublished() {
        $result = $this->Album->published(array('id','title'));
        $expected = array(
            array('Album' => array('id' => 1, 'title' => 'First Album')),
            array('Album' => array('id' => 2, 'title' => 'Second album')),
            array('Album' => array('id' => 3, 'title' => 'Third album'))
        );

        $this->assertEquals($expected, $result);
    }
}