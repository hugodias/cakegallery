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
        $page_title = "Published albums";

        if (isset($_GET['status']) && $_GET['status'] == 'draft') {
            $search_status = $_GET['status'];
            $page_title = "Drafts";
            $is_draft = true;
        }

        $galleries = $this->Album->findAllByStatus($search_status);

        $this->set(compact('galleries', 'page_title', 'search_status'));
    }

} 