<?php
class Album extends GalleryAppModel {
	public $name = 'Album';
	public $tablePrefix = 'gallery_';
	public $order = 'Album.id DESC';
	public $hasMany = array(
		'Picture' => array(
			'className' => 'Gallery.Picture',
			'conditions' => array('Picture.main_id' => null),
			'order' => array('Picture.order' => 'ASC')
		)
	);

	public $validate = array(
		'title' => array(
			array(
				'rule' => array('notEmpty'),
				'message' => 'A title is required.'
			)
		)
	);

	/**
	 * Create a folder in webroot/files/gallery/{album_id}
	 * for this folder after save it (only on create)
	 * @param $created
	 * @param array $options
	 */
	public function afterSave($created, $options = array()) {
		if ($created) {
			$this->_createGalleryFolder($this->data['Album']['id']);
		}
	}


	/**
	 * Get all published albums
	 * @return mixed
	 */
	public function published($fields = null) {
		return $this->find('all', array(
			'conditions' => array(
				'Album.status' => 'published'
			),
			'recursive' => 2,
			'fields' => $fields
		));
	}
	
	
	
	/**
	 * Get all draft albums
	 * @return array
	 */
	public function draft($fields = null) {
		return $this->find('all', array(
			'conditions' => array(
				'Album.status' => 'draft'
			),
			'recursive' => 2,
			'fields' => $fields
		));	    
	}


	/**
	 * Create an album record on database
	 * @param $model
	 * @param $model_id
	 */
	public function createAlbumAndRedirect($model = null, $model_id = null) {
		# If there is a Model and ModelID on parameters, get or create a folder for it
		if ($model && $model_id) {
			# Searching for folder that belongs to this particular $model and $model_id
			if (!$album = $this->_getModelAlbum($model, $model_id)) {
				# If there is no Album , lets create one for it
				$album = $this->_createAlbum($model, $model_id);
			}
		} else {
			# If there is no model on parameters, lets create a generic folder
			$album = $this->_createAlbum(null, null);
		}

		return $album;
	}

	/**
	 *
	 * @param null $model
	 * @param null $model_id
	 * @return mixed
	 */
	private function _getModelAlbum($model = null, $model_id = null) {
		return $this->find('first', array(
			'conditions' => array(
				'Album.model' => $model,
				'Album.model_id' => $model_id
			)));
	}

	/**
	 * Create a empty album
	 * @param null $model
	 * @param null $model_id
	 * @return mixed
	 */
	private function _createAlbum($model = null, $model_id = null) {
		$this->save(array(
			'Album' => array(
				'model' => $model,
				'model_id' => $model_id,
				'status' => 'published',
				'tags' => '',
				'title' => $this->_generateAlbumName($model, $model_id)
			)
		));
		return $this->read(null);
	}


	/**
	 * Generate a random album name
	 * @param null $model
	 * @param null $model_id
	 * @return string
	 */
	private function _generateAlbumName($model = null, $model_id = null) {
		$name = 'Album - ' . rand(111, 999);

		if ($model && $model_id) {
			$name = Inflector::humanize('Album ' . $model . ' - ' . $model_id);
		}

		return $name;
	}

	/**
	 * Create an folder at webroot/files/gallery to store album pictures
	 * @param $folder_name
	 */
	private function _createGalleryFolder($folder_name) {
		# Folder to store galleries folders
		$galleries_path = WWW_ROOT . 'files' . DS . 'gallery';

		# Gallery folder
		$folder_path = $galleries_path . DS . $folder_name;

		# Check if webroot/files and webroot/files/gallery folder are created
		if (!is_dir($galleries_path)) {
			if (!is_dir(WWW_ROOT . 'files')) {
				# Create webroot/files folder if dont exists
				mkdir(WWW_ROOT . 'files', 755);
			}
			# Create webroot/files/gallery folder if dont exists
			mkdir($galleries_path, 755);
		}
		if (!is_dir($folder_path)) {
			# Create gallery folder if dont exists
			mkdir($folder_path, 755);
		}
	}
}

?>