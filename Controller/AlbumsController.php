<?php

class AlbumsController extends GalleryAppController {

	public $helpers = array('Form' => array('className' => 'Gallery.CakePHPFTPForm'));

	public function add() {
	}

	public function create() {
		if ($this->request->is('post')) {
			if ($this->Album->save($this->request->data)) {
				# Create folder at files/gallery/{album_id}
				mkdir(WWW_ROOT . 'files/gallery/' . $this->Album->id);
				$this->redirect(array('action' => 'upload', $this->Album->id));
			} else {
				$this->Error->set($this->Album->invalidFields());
			}
		}
	}

	public function update() {
		if ($this->request->is('post')) {
			if ($this->Album->save($this->request->data)) {
				echo "You configurations are saved.";
			}
		}
		$this->render(false, false);
	}

	public function upload($model = null, $model_id = null) {
		ini_set("memory_limit", "10000M");

		if (isset($this->params['gallery_id']) && !empty($this->params['gallery_id'])) {
			$album = $this->Album->findById($this->params['gallery_id']);
		} else {
			# If the gallery doesnt exists, create a new one and redirect back to this page with the
			# gallery_id
			$this->_createAlbumAndRedirect($model, $model_id);
		}

		$files = $album['Picture'];

		$this->set(compact('model', 'model_id', 'album', 'files'));
	}


	public function delete($id) {
		$this->Album->id = $id;

		$album = $this->Album->read(null);

		if (count($album['Picture'])) {
			foreach ($album['Picture'] as $pic) {
				# Original
				if ($pic['style'] = 'full') {
					# Remove from database and all files
					$this->Picture->_deletePicture($pic['id']);
				}
			}
		}
	}


	private function _createAlbumAndRedirect($model, $model_id) {
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

		$this->redirect(array(
			'action' => 'upload',
			'gallery_id' => $album['Album']['id']
		));
	}

	private function _getModelAlbum($model = null, $model_id = null) {
		return $this->Album->find('first', array(
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
		$this->Album->save(array(
			'Album' => array(
				'model' => $model,
				'model_id' => $model_id,
				'status' => 'published',
				'tags' => '',
				'title' => $this->_generateAlbumName($model, $model_id)
			)
		));
		return $this->Album->read(null);
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
}

?>
