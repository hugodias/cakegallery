<?php
App::uses('FormHelper', 'View/Helper');

class GalleryHelper extends Helper{
  public function button($model = null, $model_id = null){
    return $this->_View->Html->link('Upload pictures', array(
      'controller' => 'albums',
      'action' => 'upload',
      'plugin' => 'gallery',
      $model,
      $model_id
      ));
  }

  public function new_gallery_button() {
    return $this->_View->Html->link('New Gallery', array(
      'controller' => 'albums',
      'action' => 'upload',
      'plugin' => 'gallery'
      ));
  }
}
