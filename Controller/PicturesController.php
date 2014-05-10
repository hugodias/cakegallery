<?php

class PicturesController extends GalleryAppController {
	public $components = array('Gallery.Util');
	public $uses = array('Gallery.Album', 'Gallery.Picture');

	public function upload() {
		$album_id = $_POST['album_id'];

		# Resize attributes configured in bootstrap.php
		$resize_attrs = $this->_getResizeSize();

		if ($_FILES) {
			$file = $_FILES['file'];

			try {
				# Check if the file have any errors
				$this->_checkFileErrors($file);

				# Get file extention
				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

				# Validate if the file extention is allowed
				$this->_validateExtentions($ext);

				# Generate a random filename
				$filename = $this->Util->getToken();

				$path = WWW_ROOT . 'files/gallery/' . $album_id . '/' . $filename . '.' . $ext;

				$main_id = $this->_upload_file(
					$path,
					$album_id,
					$file['name'],
					$file['size'],
					$file['tmp_name'],
					$resize_attrs['width'],
					$resize_attrs['height'],
					$resize_attrs['action'],
					true);


				# Create extra pictures from the original one
				$this->_createExtraImages(Configure::read('GalleryOptions.Pictures.styles'), $file['name'], $file['size'], $file['tmp_name'], $album_id, $main_id);

			} catch (ForbiddenException $e) {
				$response = $e->getMessage();
				return new CakeResponse(
					array(
						'status' => 401,
						'body' => json_encode($response)
					)
				);
			}
		}

		$this->render(false, false);
	}


	private function _checkFileErrors($file) {
		if (!$file['error'] == 0) {
			throw new ForbiddenException("Upload failed. Check your file.");
		}
	}

	private function _validateExtentions($ext) {
		if (!in_array(strtolower($ext), Configure::read('GalleryOptions.File.allowed_extensions'))) {
			throw new ForbiddenException("You cant upload this kind of file.");
		}
	}

	/**
	 * @param $styles
	 * @param $filename
	 * @param $filesize
	 * @param $tmp_name
	 * @param $album_id
	 * @param $main_id
	 */
	public function _createExtraImages($styles, $filename, $filesize, $tmp_name, $album_id, $main_id) {
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if (count($styles)) {
			foreach ($styles as $name => $style) {
				$width = $style[0];
				$height = $style[1];
				$crop = $style[2] ? "crop" : "";

				$custom_filename = $name . '-' . $this->Util->getToken();

				$path = WWW_ROOT . 'files/gallery/' . $album_id . '/' . $custom_filename . '.' . $ext;

				try{
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
						$main_id,
						$name
					);
				} catch (ForbiddenException $e){
					throw new ForbiddenException($e->getMessage());
				}
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
	private function _upload_file($path, $album_id, $filename, $filesize, $tmp_name, $width = 0, $height = 0, $action, $save = false, $main_id = null, $style = 'full') {
		# Copy the file to the folder
		if (copy($tmp_name, $path)) {

			# Resize only if the width or the height has benn informed
			if (!!$width || !!$height) {
				# Image transformation / Manipulation

				try{
					$path = $this->resizeCrop($path, $width, $height, $action);
				} catch (InternalErrorException $e){
					throw new ForbiddenException($e->getMessage());
				}
			}

			if ($save) {
				return $this->_savePicture($album_id, $filename, $filesize, $path, $main_id, $style);
			}

			return null;
		} else {
			throw new ForbiddenException("Upload failed. Check your folders permissions.");
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
	private function _savePicture($album_id, $filename, $filesize, $path, $main_id = null, $style = 'full') {
		$this->Picture->create();

		# Save the file in database
		$aux = array(
			'Picture' => array(
				'album_id' => $album_id,
				'name' => $filename,
				'size' => $filesize,
				'path' => $path,
				'main_id' => $main_id,
				'style' => $style
			));

		$this->Picture->save($aux);

		return $this->Picture->id;
	}


	public function resizeCrop($path, $width = 0, $height = 0, $action = null) {
		ini_set("memory_limit", "10000M");

		App::import(
			'Vendor',
			'Gallery.Zebra_Image',
			array('file' => 'ZebraImage.class.php')
		);

		$image = new Zebra_Image();

		# Load image
		$image->source_path = $path;

		# The target will be the same image
		$target = $path;

		# File Extension
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

		# Convert PNG files to JPG if configured on bootstrap.php
		if(Configure::read('GalleryOptions.Pictures.png2jpg') && $ext == "png" ){
			# Flag to check must delete the jpg file
			define("DELETE_PNG", 0x1);

			# Store JPG file location
			$jpg_file = $target;

			# Update target path with JPG extension
			$target = str_replace(array('.png','.PNG'), '.jpg', $path);
		}

		# The target will be the same image
		$image->target_path = $target;

		# JPG quality
		$image->jpeg_quality = Configure::read('GalleryOptions.Pictures.jpg_quality');

		if($action == "crop")
			$action = ZEBRA_IMAGE_CROP_CENTER;

		if(!$image->resize($width, $height, $action)){
			// if there was an error, let's see what the error is about
			switch ($image->error) {

				case 1:
					throw new InternalErrorException('Source file could not be found!');
					break;
				case 2:
					throw new InternalErrorException('Source file is not readable!');
					break;
				case 3:
					throw new InternalErrorException('Could not write target file!');
					break;
				case 4:
					throw new InternalErrorException('Unsupported source file format!');
					break;
				case 5:
					throw new InternalErrorException('Unsupported target file format!');
					break;
				case 6:
					throw new InternalErrorException('GD library version does not support target file format!');
					break;
				case 7:
					throw new InternalErrorException('GD library is not installed!');
					break;
				case 8:
					throw new InternalErrorException('"chmod" command is disabled via configuration!');
					break;

			}
		} else{
			# Delete PNG file if needed
			if(DELETE_PNG){
				unlink($jpg_file);
			}

			return $target;
		}
	}

	public function delete($id) {
		# Delete the picture and all its versions
		$this->Picture->_deletePicture($id);

		$this->render(false, false);
	}

	# Sort photos
	public function sort() {
		if ($this->request->is('post'))
		{
			$order = explode(",",$_POST['order']);
			$i = 1;
			foreach ($order as $photo) {
				$this->Picture->read(null,$photo);
				$this->Picture->set('order',$i);
				$this->Picture->save();
				$i++;
			}
		}

		$this->render(false, false);
	}

	/**
	 * Return configured main image resize attributes
	 * @return array
	 */
	private function _getResizeSize() {
		$width = $height = 0;
		$crop = "";

		if (Configure::check('GalleryOptions.Pictures.resize_to.0')) {
			$width = Configure::read('GalleryOptions.Pictures.resize_to.0');
		}

		if (Configure::check('GalleryOptions.Pictures.resize_to.1')) {
			$height = Configure::read('GalleryOptions.Pictures.resize_to.1');
		}

		$crop = Configure::read('GalleryOptions.Pictures.resize_to.2');
		$action = $crop ? "crop" : "";

		return array(
			'width' => $width,
			'height' => $height,
			'action' => $action
		);
	}
}

?>
