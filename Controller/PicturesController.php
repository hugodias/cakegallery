<?php

class PicturesController extends GalleryAppController {
	public $components = array('Gallery.Util');
	public $uses = array('Gallery.Album', 'Gallery.Picture');

	public function upload(){
		$album_id = $_POST['album_id'];

		$width = Configure::read('GalleryOptions.Pictures.resize_to.0');
		$height = Configure::read('GalleryOptions.Pictures.resize_to.1');
		$crop = Configure::read('GalleryOptions.Pictures.resize_to.2');

		$action = $crop ? "crop" : "";

		if ($_FILES) {
			$file = $_FILES['file'];
			if ($file['error'] == 0) {
				# Get file extention
				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
				$filename = $this->Util->getToken();

				$path = WWW_ROOT . 'files/gallery/' . $album_id . '/' . $filename . '.' . $ext;

				$main_id = $this->_upload_file(
					$path,
					$album_id,
					$file['name'],
					$file['size'],
					$file['tmp_name'],
					$width,
					$height,
					$action,
					true);


				# Create extra pictures from the original one
				$this->_createExtraImages( Configure::read('GalleryOptions.Pictures.styles'), $file['name'], $file['size'], $file['tmp_name'], $album_id, $main_id );
			}
		}

		$this->render(false,false);
	}


	/**
	 * @param $styles
	 * @param $filename
	 * @param $filesize
	 * @param $tmp_name
	 * @param $album_id
	 * @param $main_id
	 */
	public function _createExtraImages($styles, $filename, $filesize, $tmp_name, $album_id, $main_id){
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if(count($styles)){
			foreach($styles as $name => $style){
				$width = $style[0];
				$height = $style[1];
				$crop = $style[2] ? "crop" : "";

				$custom_filename = $name . '-' . $this->Util->getToken();

				$path = WWW_ROOT . 'files/gallery/' . $album_id . '/' . $custom_filename . '.' . $ext;

				$this->_upload_file(
					$path,
					$album_id,
					$name . '-' . $filename,
					$filesize,
					$tmp_name,
					$width,
					$height,
					$crop,
					true,
					$main_id
				);
			}
		}
	}


	/**
	 * Upload the image to WWW_ROOT/files/gallery/{album_id}/picture.jpg
	 * Optionaly save it to database
	 * @param $path
	 * @param $album_id
	 * @param $filename
	 * @param $filesize
	 * @param $tmp_name
	 * @param $width
	 * @param $height
	 * @param $action
	 * @param bool $save
	 * @param null $main_id
	 * @return mixed
	 */
	private function _upload_file($path, $album_id, $filename, $filesize, $tmp_name, $width, $height, $action, $save = false, $main_id = null) {
		# Copy the file to the folder
		if (copy($tmp_name, $path)) {

			# Image transformation / Manipulation
			$this->resizeCrop($path, $width, $height, $action);

			if ($save) {
				return $this->_savePicture($album_id, $filename, $filesize, $path, $main_id);
			}
		}
	}

	/**
	 * Save picture information in database
	 * @param $album_id
	 * @param $filename
	 * @param $filesize
	 * @param $path
	 * @param null $main_id
	 * @return mixed
	 */
	private function _savePicture($album_id, $filename, $filesize, $path, $main_id = null){
		$this->Picture->create();

		# Save the file in database
		$aux = array(
			'Picture' => array(
				'album_id' => $album_id,
				'name' => $filename,
				'size' => $filesize,
				'path' => $path,
				'main_id' => $main_id
			));

		$this->Picture->save($aux);

		return $this->Picture->id;
	}


	public function resizeCrop($path, $width = 0, $height = 0, $action = '') {
		ini_set("memory_limit", "10000M");
		App::import(
			'Vendor',
			'Gallery.Img',
			array('file' => 'Img.class.php')
		);
		$imgClass = new Img();

		$imgClass->carrega($path);
		$imgClass->redimensiona($width, $height, $action);
		$imgClass->png2jpg();
		$imgClass->grava($path, 85);
	}

	public function delete($id) {
		if ($file = $this->Picture->find('first', array('conditions' => array('Picture.user_id' => $this->Auth->user('id'), 'Picture.id' => $id)))) {
			if (unlink($file['Picture']['path'])) {
				$this->Picture->delete($file['Picture']['id']);
				$this->redirect($this->referer());
			}
		}

		$this->render(false, false);
	}
}

?>
