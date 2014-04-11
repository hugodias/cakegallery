<?php

/**
 * @var $this View
 */
class GalleryAppController extends AppController {

	public function beforeFilter() {
		$this->_checkRequeriments();
	}

	private function _checkRequeriments() {
		$files_path = WWW_ROOT . 'files/';
		if (!file_exists($files_path)) {
			mkdir($files_path, 0755);
		}
		$galleries_path = $files_path . 'gallery/';
		if (!file_exists($galleries_path)) {
			mkdir($galleries_path, 0755);
		}
	}

} 