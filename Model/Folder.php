<?php

class Folder extends GalleryAppModel {
	public $name = 'Folder';
	public $order = 'Folder.id DESC';
	public $hasMany = array('Gallery.Record');

	public $validate = array(
		'title' => array(
			array(
				'rule' => array('notEmpty'),
				'message' => 'A title is required.'
			)
		)
	);

	/**
	 * Create a folder in webroot/files/gallery/{folder_id}
	 * for this folder after save it (only on create)
	 * @param $created
	 * @param array $options
	 */
	public function afterSave($created, $options = array()) {
		if ($created) {
			$folder_path = WWW_ROOT . 'files/gallery/' . $this->data['Folder']['id'];
			if (!file_exists($folder_path)) {
				mkdir($folder_path, 0755);
			}
		}
	}
}

?>