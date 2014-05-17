<?php
App::uses('Picture', 'Gallery.Picture');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class PictureTest extends CakeTestCase
{
    public $fixtures = array('plugin.gallery.gallery_picture', 'plugin.gallery.gallery_album');
    public $Picture;
    public $Album;


    public function setUp()
    {
        parent::setUp();
        $this->Picture = ClassRegistry::init('Gallery.Picture');
        $this->Album = ClassRegistry::init('Gallery.Album');
    }

    public function testGetResizeToSize()
    {
        $results = $this->Picture->getResizeToSize();

        $this->assertTrue(is_array($results));
    }


    public function testStylesAfterFind()
    {
        $picture = $this->Picture->find('first');

        $this->assertTrue(is_array($picture['Picture']['styles']));
    }


    public function testGetNextNumber()
    {
        $nextNumber = $this->Picture->getNextNumber(1);

        $this->assertEquals(3, $nextNumber);
    }

    public function testSavePicture()
    {
        $picture_without_album = $this->Picture->savePicture(null, null, null, null);

        $this->assertFalse($picture_without_album);

        $picture_without_size = $this->Picture->savePicture(1, null, null, null);

        $this->assertFalse($picture_without_size);

        $picture_without_path = $this->Picture->savePicture(1, null, null, null);

        $this->assertFalse($picture_without_path);
    }

    public function testGetFileSize()
    {
        $this->assertFalse($this->Picture->getFileSize(null));
    }

    public function testGenerateFilePath()
    {
        $should_return_false = $this->Picture->generateFilePath();
        $this->assertFalse($should_return_false);


        $should_return_path = $this->Picture->generateFilePath(1, 'myfile.jpg');
        $this->assertTrue(strpos($should_return_path, 'myfile.jpg') !== false);
    }


    public function testUploadFile()
    {

        $album = $this->Album->init();

        $tmp_file = "/tmp/111111111.jpg";

        # Generate custom image to test upload
        $im = imagecreatetruecolor(120, 20);
        $text_color = imagecolorallocate($im, 233, 14, 91);
        imagestring($im, 1, 5, 5, 'A Simple Text String', $text_color);
        imagejpeg($im, $tmp_file);

        # Generate path to save file
        $file_path = $this->Picture->generateFilePath($album['Album']['id'], 'picturetests.jpg');

        $file = new File($file_path);

        # Get resize options
        $resize_attrs = $this->Picture->getResizeToSize();

        # Upload and save
        $should_return_3 = $this->Picture->uploadFile(
            $file_path,
            $album['Album']['id'],
            'samplepicture.jpg',
            $tmp_file,
            $resize_attrs['width'],
            $resize_attrs['height'],
            $resize_attrs['action'],
            true
        );

        # File was saved?
        $this->assertEqual(3, $should_return_3);

        # File was uploaded?
        $this->assertTrue($file->exists());


        # Should rise exception
        try {
            $this->Picture->uploadFile(
                $file_path,
                null,
                'samplepicture.jpg',
                $tmp_file,
                $resize_attrs['width'],
                $resize_attrs['height'],
                $resize_attrs['action'],
                true
            );
        } catch (ForbiddenException $e) {
            $this->assertEqual($e->getMessage(), "The album ID is required");
        }

    }


    public function testResizeCrop()
    {

    }


    public function testDeletePictures()
    {

    }


}