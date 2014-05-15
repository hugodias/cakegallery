<?php
App::uses('ModelBehavior', 'Model');
App::uses('Gallery.Album', 'Model');
App::uses('Gallery.Picture', 'Model');

class GalleryBehavior extends ModelBehavior
{

    public function setup(Model $Model, $settings = array())
    {
        if (!isset($this->settings[$Model->alias])) {
            $this->settings[$Model->alias] = array(
                'option1_key' => 'option1_default_value',
                'option2_key' => 'option2_default_value',
                'option3_key' => 'option3_default_value',
            );
        }
        $this->settings[$Model->alias] = array_merge(
            $this->settings[$Model->alias],
            (array)$settings
        );
    }


    /**
     * Get model gallery
     * @param Model $Model
     * @return mixed
     */
    public function getGallery(Model $Model)
    {
        $Album = new Album();
        return $Album->find(
            'first',
            array(
                'conditions' => array(
                    'model' => $Model->alias,
                    'model_id' => $Model->id
                )
            )
        );
    }

}
