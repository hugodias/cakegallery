<?php

class RecordsController extends GalleryAppController {
	public $components = array('Gallery.Util');
	public $uses = array('Gallery.Folder', 'Gallery.Record');

	public function add() {
		$folder_id = $_POST['folder_id'];
		$folder_info = $this->Folder->findById($folder_id);

		$default_name = $folder_info['Folder']['default_name'];
		$width = $folder_info['Folder']['width'];
		$height = $folder_info['Folder']['height'];
		$th_width = $folder_info['Folder']['th_width'];
		$th_height = $folder_info['Folder']['th_height'];
		$action = $folder_info['Folder']['action'];


		if ($_FILES) {
			$file = $_FILES['file'];
			if ($file['error'] == 0) {
				# Get file extention
				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
				$thumbnail_path = null;

				if (empty($default_name)) {
					$title = $file['name'];
				} else {
					$title = $default_name . '-' . $this->Record->getNextNumber($folder_id) . '.' . $ext;
				}

				# Create the path where the file will be stored
				$path = WWW_ROOT . 'files/gallery/' . $folder_id . '/' . $title;

				# Generate a thumbnail
				if (!empty($th_width) && !empty($th_height)) {
					$thumbnail_path = $this->_generate_thumbnail($th_width,$th_height, $folder_id, $title, $file);
				}

				# Upload file
				$this->_upload_file(
					$path,
					$folder_id,
					$title,
					$file['size'],
					$file['tmp_name'],
					$width,
					$height,
					$action,
					$thumbnail_path,
					true);

			}
		}

		$this->render(false, false);
	}

	/**
	 * Generate a thumbnail for the picture
	 * @param $th_width
	 * @param $th_height
	 * @param $folder_id
	 * @param $title
	 * @param $file
	 * @return string
	 */
	private function _generate_thumbnail($th_width, $th_height, $folder_id, $title, $file){
		$th_folder_path = WWW_ROOT . 'files/gallery/' . $folder_id . '/TH/';

		if (!file_exists($th_folder_path)) {
			mkdir($th_folder_path, 0755);
		}

		# TH path for upload
		$path_th = $th_folder_path . $title;

		# Save the thumbnail_path
		$thumbnail_path = $path_th;

		# Upload thumbnail
		$this->_upload_file(
			$path_th,
			$folder_id,
			$title,
			$file['size'],
			$file['tmp_name'],
			$th_width,
			$th_height,
			'crop');

		return $thumbnail_path;
	}

	private function _upload_file($path, $folder_id, $filename, $filesize, $tmp_name, $width, $height, $action, $thumbnail_path = null, $save = false) {
		# Copy the file to the folder
		if (copy($tmp_name, $path)) {

			if ($save) {
				# Save the file in database
				$aux = array(
					'Record' => array(
						'folder_id' => $folder_id,
						'name' => $filename,
						'size' => $filesize,
						'path' => $path,
						'thumbnail_path' => $thumbnail_path
					));
				$this->Record->save($aux);
			}

			# Resize e crop para as dimensoes da pasta
			$this->resizeCrop($path, $width, $height, $action);
		}
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
		if ($file = $this->Record->find('first', array('conditions' => array('Record.user_id' => $this->Auth->user('id'), 'Record.id' => $id)))) {
			if (unlink($file['Record']['path'])) {
				$this->Record->delete($file['Record']['id']);
				$this->redirect($this->referer());
			}
		}

		$this->render(false, false);
	}
}

?>
