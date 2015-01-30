<?php
App::uses('ModelBehavior', 'Model');
App::uses('Album', 'Gallery.Model');
App::uses('Picture', 'Gallery.Model');

class GalleryBehavior extends ModelBehavior
{
    public function afterFind(Model $Model, $results, $primary = false)
    {
        foreach ($results as $key => $val) {

            if ($this->settings[$Model->alias]['automatic']) {
                $gallery = $this->getGallery($Model, $val[$Model->name]['id']);

                if ($gallery) {
                    $results[$key]['Gallery'] = $gallery;
                    $results[$key]['Gallery']['numPictures'] = count($gallery['Picture']);
                }

            }
        }
        return $results;
    }

    public function setup(Model $Model, $settings = array())
    {
        if (!isset($this->settings[$Model->alias])) {
            $this->settings[$Model->alias] = array(
                'automatic' => true
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
    public function getGallery(Model $Model, $object_id = null)
    {
        $Album = new Album();

        if (!$object_id) {
            $object_id = $Model->id;
        }

        return $Album->getAttachedAlbum($Model->alias, $object_id);
    }
}
