<?php
App::uses('FormHelper', 'View/Helper');


class GalleryHelper extends Helper{
  public function button($model = null, $model_id = null){
    return $this->_View->Html->link('Upload pictures', array(
      'controller' => 'folders',
      'action' => 'upload',
      'plugin' => 'gallery',
      $model,
      $model_id
      ));
  }
}
