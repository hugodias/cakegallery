<?php

class GalleryController extends GalleryAppController
{
    public $uses = array('Gallery.Album');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function index()
    {
        $search_status = "published";
        $page_title = __d('gallery', 'Published galleries');

        if (isset($this->request->query['status']) && $this->request->query['status'] == 'draft') {
            $search_status = $this->request->query['status'];
            $page_title = __d('gallery', 'Drafts');
        }

        $galleries = $this->Album->findAllByStatus($search_status);

        $this->set(compact('galleries', 'page_title', 'search_status'));
    }

}
