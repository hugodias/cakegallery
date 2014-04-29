<?php
class GalleryController extends GalleryAppController{
	public $uses = array('Gallery.Album');

	public function index(){
		$this->set('galleries', $this->Album->find_all_published());
	}

	public function docs(){}

} 