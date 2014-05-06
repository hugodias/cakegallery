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
			$folder_path = WWW_ROOT . 'files/gallery/' . $this->data['Album']['id'];
			if (!file_exists($folder_path)) {
				mkdir($folder_path, 0755);
			}
		}
	}


	/**
	 * Get all published albums
	 * @return mixed
	 */
	public function find_all_published(){
		return $this->find('all', array(
			'conditions' => array(
				'Album.status' => 'published'
			),
			'recursive' => 2
		));
	}
}

?>