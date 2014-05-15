<?php
App::uses('Album', 'Gallery.Model');

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

    public function testCreateStandAloneAlbum()
    {
        $result = $this->Album->createAlbumAndRedirect();

        $this->assertTrue(isset($result['Album']));
    }

    public function testCreateAttachedAlbum()
    {
        $result = $this->Album->createAlbumAndRedirect('product', 1);

        $this->assertTrue($result['Album']['model'] == "product");

        $this->assertTrue($result['Album']['model_id'] == 1);
    }

}