<?php
class Picture extends GalleryAppModel {
	public $name = 'Picture';
	public $tablePrefix = 'gallery_';
	public $belongsTo = array('Gallery.Album');

	public function getNextNumber($album_id) {
		return (int)$this->find('count', array('conditions' => array('Picture.album_id' => $album_id))) + 1;
	}
}

?>
