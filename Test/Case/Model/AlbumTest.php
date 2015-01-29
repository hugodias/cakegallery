<?php
App::uses('Album', 'Gallery.Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class AlbumTest extends CakeTestCase
{
    public $fixtures = array('plugin.gallery.gallery_album');
    public $Album;

    public function setUp()
    {
        parent::setUp();
        $this->Album = ClassRegistry::init('Gallery.Album');
    }

    public function testPublished()
    {
        $result = $this->Album->published(array('id', 'status'));
        $expected = array(
            array('Album' => array('id' => 3, 'status' => 'published'), 'Picture' => array()),
            array('Album' => array('id' => 1, 'status' => 'published'), 'Picture' => array())
        );

        $this->assertEquals($expected, $result);
    }

    public function testDraft()
    {
        $result = $this->Album->draft(array('id', 'status'));
        $expected = array(
            array('Album' => array('id' => 2, 'status' => 'draft'), 'Picture' => array()),
        );

        $this->assertEquals($expected, $result);
    }

    public function testAlbumNameNotEmpty()
    {
        $album = $this->Album->init();

        $this->assertTrue(!empty($album['Album']['title']));
    }

    public function testCreateStandAloneAlbum()
    {
        $result = $this->Album->init();

        $this->assertTrue(isset($result['Album']));
    }

    public function testCreateAttachedAlbum()
    {
        $result = $this->Album->init('product', 1);

        $this->assertTrue($result['Album']['model'] == "product");

        $this->assertTrue($result['Album']['model_id'] == 1);
    }

    public function testCreateAlbumFolder()
    {
        $album = $this->Album->init();

        $this->assertTrue(is_dir(WWW_ROOT . 'files' . DS . 'gallery' . DS . $album['Album']['id']));
    }

}