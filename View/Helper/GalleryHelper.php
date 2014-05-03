<?php
App::uses('FormHelper', 'View/Helper');

class GalleryHelper extends Helper{
  public function button($model = null, $model_id = null, $html_options = array()){
    return $this->_View->Html->link('Upload pictures', array(
      'controller' => 'albums',
      'action' => 'upload',
      'plugin' => 'gallery',
      $model,
      $model_id
      ), $html_options);
  }

  public function new_gallery_button($html_options = array()) {
    return $this->_View->Html->link('New Gallery', array(
      'controller' => 'albums',
      'action' => 'upload',
      'plugin' => 'gallery'
      ), $html_options);
  }
}
