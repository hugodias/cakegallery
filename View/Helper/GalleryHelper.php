<?php
App::uses('AppHelper', 'View/Helper');
App::uses('FormHelper', 'View/Helper');

class GalleryHelper extends AppHelper {
	public function button($model = null, $model_id = null, $html_options = array()) {
		return $this->_View->Html->link('Upload pictures', array(
			'controller' => 'albums',
			'action' => 'upload',
			'plugin' => 'gallery',
			$model,
			$model_id
		), $html_options);
	}

	public function new_gallery_button($html_options = array()) {
		# Icons on link
		$html_options['escape'] = false;

		return $this->_View->Html->link('<i class="fa fa-plus"></i> New Album', array(
			'controller' => 'albums',
			'action' => 'upload',
			'plugin' => 'gallery'
		), $html_options);
	}
}
