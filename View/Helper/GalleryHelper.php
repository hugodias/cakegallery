<?php
App::uses('AppHelper', 'View/Helper');
App::uses('FormHelper', 'View/Helper');

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
            'Upload pictures',
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
}
