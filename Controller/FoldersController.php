<?php

class FoldersController extends GalleryAppController {

	public $helpers = array('Form' => array('className' => 'Gallery.CakePHPFTPForm'));

	public function add() {
	}

	public function create() {
		if ($this->request->is('post')) {
			if ($this->Folder->save($this->request->data)) {
				# Create folder at files/gallery/{folder_id}
				mkdir(WWW_ROOT . 'files/gallery/' . $this->Folder->id);
				$this->redirect(array('action' => 'upload', $this->Folder->id));
			} else {
				$this->Error->set($this->Folder->invalidFields());
			}
		}
	}

	public function update() {
		if ($this->request->is('post')) {
			if ($this->Folder->save($this->request->data)) {
				echo "You configurations are saved.";
			}
		}
		$this->render(false, false);
	}

	public function upload($model = null, $model_id = null) {
		ini_set("memory_limit", "10000M");

		# If there is a Model and ModelID on parameters, get or create a folder for it
		if ($model && $model_id) {
			# Searching for folder that belongs to this particular $model and $model_id
			if (!$folder = $this->_getModelFolder($model, $model_id)) {
				# If there is no Folder , lets create one for it
				$folder = $this->_createFolder($model, $model_id);
			}
		} else {
			# If there is no model on parameters, lets create a generic folder
			$folder = $this->_createFolder(null, null);
		}

		$files = $folder['Record'];

		$this->set(compact('model', 'model_id', 'folder', 'files'));
	}

	private function _getModelFolder($model = null, $model_id = null) {
		return $this->Folder->find('first', array(
			'conditions' => array(
				'Folder.model' => $model,
				'Folder.model_id' => $model_id
			)));
	}

	private function _createFolder($model = null, $model_id = null) {
		$this->Folder->save(array(
			'Folder' => array(
				'model' => $model,
				'model_id' => $model_id,
				'title' => $this->_generateFolderName($model, $model_id)
			)
		));
		return $this->Folder->read(null);
	}


	private function _generateFolderName($model = null, $model_id = null){
		$name = 'Gallery - ' . rand(111,999);

		if($model && $model_id){
			$name = Inflector::humanize('Gallery ' . $model . ' - ' . $model_id);
		}

		return $name;
	}
}

?>
