<?php
App::uses('AppHelper', 'View/Helper');
App::uses('FormHelper', 'View/Helper');
App::uses('Album', 'Gallery.Model');

class GalleryHelper extends AppHelper
{
    /**
     * Gallery link helper
     *
     * This helper will create an link to a new or existing gallery
     *
     * If no arguments are passed this will create a new gallery everytime the link are clicked.
     *
     * When model & model_id arguments are passed, this will redirect to this specific gallery (it will also create a
     * new one if didn't exists)
     *
     * Example:
     * <?php echo $this->Gallery->button('product', 1) ?>
     *
     * @param null $model
     * @param null $model_id
     * @param array $html_options
     * @return string
     */
    public function link($model = null, $model_id = null, $html_options = array())
    {
        return $this->_View->Html->link(
            __('Upload pictures'),
            array(
                'controller' => 'albums',
                'action' => 'upload',
                'plugin' => 'gallery',
                $model,
                $model_id
            ),
            $html_options
        );
    }

    /**
     * Get cover picture from an album (The first picture)
     *
     * @example
     * <?php echo $this->Gallery->cover('product', 10) ?>
     *
     * @param null $model
     * @param null $model_id
     * @param null $album_id
     * @param string $style
     * @return string
     */
    public function cover($model = null, $model_id = null, $album_id = null, $style = 'medium')
    {
        if ($album = $this->getAlbum($model, $model_id, $album_id)) {
            if (!empty($album['Picture'][0])) {
                return '<img src="' . $album['Picture'][0]['styles'][$style] . '" alt="">';
            }
        }
    }

    /**
     * Render a gallery with thumbnails
     *
     * @example
     * <?php $this->Gallery->showroom('product', 10) ?>
     *
     * @param null $model
     * @param null $model_id
     * @param null $album_id
     * @return string
     */
    public function showroom(
        $model = null,
        $model_id = null,
        $album_id = null,
        $style = 'medium',
        $html_options = array('jquery' => true, 'swipebox' => true))
    {

        $album = $this->getAlbum($model, $model_id, $album_id);

        if (!empty($album)) {
            # Load scripts for the showroom (jquery, bootstrap, swipebox)
            $this->_loadScripts($html_options);

            # Render the showroom
            $this->showroomTmpl($album, $style);
        } else {
            # Album doesn't exists
            $this->_noPhotosMessageTmpl();
        }

        return;
    }

    /**
     * This method use a album object to loop in all pictures and
     * show them using a bootstrap html pattern.
     *
     * @param $album
     */
    public function showroomTmpl($album, $style = 'medium')
    {
        if (empty($album['Picture'])) {
            $this->_noPhotosMessageTmpl();
        } else {
            foreach ($album['Picture'] as $picture) {
                $this->_thumbnailTmpl($picture, $style);
            }
        }
    }


    /**
     * Get an album from $model & $model_id or $album_id
     *
     * @param null $model
     * @param null $model_id
     * @param null $album_id
     */
    private function getAlbum($model = null, $model_id = null, $album_id = null)
    {
        $Album = new Album();

        if ($album_id) {
            return $Album->findById($album_id);
        } else if ($model && $model_id) {
            return $Album->getAttachedAlbum($model, $model_id);
        }
    }

    /**
     * Load scripts for using on showroom
     *
     * @param bool $jquery
     */
    private function _loadScripts($scripts = array('jquery' => true, 'swipebox' => true))
    {
        if (!isset($scripts['jquery']) || (isset($scripts['jquery']) && $scripts['jquery'] == true)) {
            echo $this->_View->Html->script(
                'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
                array('block' => 'script'));
        }

        if (!isset($scripts['swipebox']) || (isset($scripts['swipebox']) && $scripts['swipebox'] == true)) {
            echo $this->_View->Html->css(
                'https://cdnjs.cloudflare.com/ajax/libs/jquery.swipebox/1.3.0.2/css/swipebox.min.css',
                array('block' => 'css'));
            echo $this->_View->Html->script(array(
                'https://cdnjs.cloudflare.com/ajax/libs/jquery.swipebox/1.3.0.2/js/jquery.swipebox.min.js',
                'Gallery.interface'
            ),
                array('block' => 'script'));
        }
    }

    /**
     * Thumbnail template for rendering a picture.
     *
     * The default style are 'medium'
     * @see Config/bootstrap.php#35
     *
     * @param $picture
     * @param string $style
     */
    private function _thumbnailTmpl($picture, $style = 'medium')
    {
        echo '
        <div class="col-sm-6 col-md-3">
            <div class="thumbnail">
                <a href="' . $picture['link'] . '" class="swipebox">
                    <img src="' . $picture['styles'][$style] . '" alt="">
                </a>
            </div>
        </div>
        ';
    }

    /**
     * Message for displaying when there is no album or pictures on specific album
     *
     * @param string $message
     */
    private function _noPhotosMessageTmpl($message = 'This album has no photos yet.')
    {
        echo '
            <div class="container-empty">
                <div class="img"><i class="fa fa-picture-o"></i></div>
                <h2>' . $message . '</h2>
            </div>
            ';
    }
}
