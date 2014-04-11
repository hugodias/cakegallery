<?php
App::uses('ModelBehavior', 'Model');
App::uses('Gallery.Folder', 'Model');
App::uses('Gallery.Record', 'Model');

class GalleryBehavior extends ModelBehavior {

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array(
				'option1_key' => 'option1_default_value',
				'option2_key' => 'option2_default_value',
				'option3_key' => 'option3_default_value',
			);
		}
		$this->settings[$Model->alias] = array_merge(
			$this->settings[$Model->alias], (array)$settings);
	}


	/**
	 * Get model gallery
	 * @param Model $Model
	 * @return mixed
	 */
	public function getGallery(Model $Model){
		$Folder = new Folder();
		return $Folder->find('first', array(
			'conditions' => array(
				'model' => $Model->alias,
				'model_id' => $Model->id
			)
		));
	}

	/**
	 * Append gallery to model
	 * @param Model $Model
	 * @param $results
	 * @param bool $primary
	 */
	public function afterFind(Model $Model, $results, $primary = false){
		debug($results);
	}
}
