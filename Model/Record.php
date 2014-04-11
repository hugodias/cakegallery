<?php
/**
*
*/
class Record extends GalleryAppModel
{
  public $name = 'Record';
  public $belongsTo = array('Gallery.Folder');

	public function getNextNumber($folder_id){
		return (int) $this->find('count', array('conditions' => array('Record.folder_id' => $folder_id))) + 1;
	}
}
?>
