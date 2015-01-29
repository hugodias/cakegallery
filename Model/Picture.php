<?php
App::uses('File', 'Utility');

class Picture extends GalleryAppModel
{
    public $name = 'Picture';

    public $pictureToDelete = null;

    public $tablePrefix = 'gallery_';

    public $belongsTo = array('Gallery.Album');

    public $order = 'Picture.order ASC';

    public $validate = array(
        'album_id' => array(
            'rule' => 'numeric',
            'required' => true,
            'message' => 'The album ID is required'
        ),
        'size' => array(
            'rule' => 'numeric',
            'required' => true,
            'message' => 'The SIZE is required'
        ),
        'path' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'The PATH is required'
        )
    );

    /**
    * Preserve picture ID for deleting versions after remove from database
    */
    public function beforeDelete($cascade = true)
    {
      $this->pictureToDelete = $this->id;

      return true;
    }

    /**
     * Remove all versions of the picture from the storage after delete the
     * record from the database
     */
    public function afterDelete()
    {
      if( $this->pictureToDelete ) {

        $this->deleteVersions($this->pictureToDelete);

        $this->pictureToDelete = null;
      }
    }

    /**
     * @param $album_id
     * @return int
     */
    public function getNextNumber($album_id)
    {
        return (int)$this->find('count', array('conditions' => array('Picture.album_id' => $album_id))) + 1;
    }


    public function beforeValidate($options = array())
    {
        if (empty($this->data['Picture']['size'])) {
            $file = new File($this->data['Picture']['path']);
            $this->data['Picture']['size'] = $file->size();
        }

        return true;
    }

    /**
     * @param $results
     * @param bool $primary
     * @return mixed
     */
    public function afterFind($results, $primary = false)
    {
        foreach ($results as $key => $val) {
            $root_url = WWW_ROOT;
            $relative_url = Router::url('/');

            if (isset($val['Picture']['path'])) {
                # Add custom styles
                $results[$key]['Picture']['styles'] = $this->getChild($val['Picture']['id']);

                # Add relative image path
                $results[$key]['Picture']['link'] = str_replace($root_url, $relative_url, $val['Picture']['path']);
            }
        }
        return $results;
    }

    /**
     * Return configured main image resize attributes
     * @return array
     */
    public function getResizeToSize()
    {
        $width = $height = 0;

        if (Configure::check('GalleryOptions.Pictures.resize_to.0')) {
            $width = Configure::read('GalleryOptions.Pictures.resize_to.0');
        }

        if (Configure::check('GalleryOptions.Pictures.resize_to.1')) {
            $height = Configure::read('GalleryOptions.Pictures.resize_to.1');
        }

        $crop = Configure::read('GalleryOptions.Pictures.resize_to.2');
        $action = $crop ? "crop" : "";

        return array(
            'width' => $width,
            'height' => $height,
            'action' => $action
        );
    }

    /**
     * Add image styles configured in bootstrap.php
     *
     * @param $path
     * @param array $sizes
     * @return array
     */
    public function addImageStyles($path)
    {
        # Styles configured in bootstrap.php
        $sizes = Configure::read('GalleryOptions.Pictures.styles');

        $links = array();

        if (count($sizes)) {
            $root_url = WWW_ROOT;
            $relative_url = Router::url('/');

            # Current filename
            $filename = end(explode("/", $path));

            foreach ($sizes as $sizename => $size) {
                # Filename with size prefix. E.g: small-filename.jpg
                $modified = $sizename . "-" . $filename;
                # Get final path replacing absolute URl to application relative URL
                $final_path = str_replace($root_url, $relative_url, str_replace($filename, $modified, $path));
                # Add to array
                $links[$sizename] = $final_path;
            }
        }

        return $links;
    }

    /**
     * Remove all versions from a picture from the database and from the server
     *
     * @param $id
     */
    public function deleteVersions($id)
    {
        # Remove all versions of the picture
        $pictures = $this->find(
            'all',
            array(
                'conditions' => array(
                    'Picture.main_id' => $id
                )
            )
        );

        if (count($pictures)) {
            foreach ($pictures as $pic) {
                # Remove file
                if (unlink($pic['Picture']['path'])) {
                    # Remove from database
                    $this->delete($pic['Picture']['id'], array('callback' => false));
                }
            }
        }

        return true;
    }

    /**
     * Save picture information in database
     *
     * @param $album_id
     * @param $filename
     * @param $filesize
     * @param $path
     * @param null $main_id
     * @param string $style
     * @return mixed
     */
    public function savePicture($album_id, $filename, $path, $main_id = null, $style = 'full')
    {
        $this->create();

        # Save the record in database
        $picture = array(
            'Picture' => array(
                'album_id' => $album_id,
                'name' => $filename,
                'path' => $path,
                'main_id' => $main_id,
                'style' => $style
            )
        );

        $this->save($picture);

        return $this->id;
    }


    /**
     * Resize and/or crop an image
     *
     * @param $path
     * @param int $width
     * @param int $height
     * @param null $action
     * @return mixed
     * @throws InternalErrorException
     */
    public function resizeCrop($path, $width = 0, $height = 0, $action = null)
    {
        ini_set("memory_limit", "10000M");

        # Flag
        $delete_png = false;

        $image = new Zebra_Image();

        # Load image
        $image->source_path = $path;

        # The target will be the same image
        $target = $path;

        # Get File Extension
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        # Convert PNG files to JPG if configured on bootstrap.php
        if (Configure::read('GalleryOptions.Pictures.png2jpg') && $ext == "png") {
            # Flag to check must delete the png file
            $delete_png = true;

            # Store PNG file path to delete later
            $png_file = $target;

            # Update target path with JPG extension
            $target = str_replace(array('.png', '.PNG'), '.jpg', $path);
        }

        # The target will be the same image
        $image->target_path = $target;

        # JPG quality
        $image->jpeg_quality = Configure::read('GalleryOptions.Pictures.jpg_quality');

        # Extra configs
        $image->preserve_aspect_ratio = true;
        $image->enlarge_smaller_images = true;
        $image->preserve_time = true;

        if ($action == "crop") {
            $action = ZEBRA_IMAGE_CROP_CENTER;
        }

        if (!$image->resize($width, $height, $action)) {
            // if there was an error, let's see what the error is about
            switch ($image->error) {

                case 1:
                    throw new InternalErrorException('Source file could not be found!');
                    break;
                case 2:
                    throw new InternalErrorException('Source file is not readable!');
                    break;
                case 3:
                    throw new InternalErrorException('Could not write target file!');
                    break;
                case 4:
                    throw new InternalErrorException('Unsupported source file format!');
                    break;
                case 5:
                    throw new InternalErrorException('Unsupported target file format!');
                    break;
                case 6:
                    throw new InternalErrorException('GD library version does not support target file format!');
                    break;
                case 7:
                    throw new InternalErrorException('GD library is not installed!');
                    break;
                case 8:
                    throw new InternalErrorException('"chmod" command is disabled via configuration!');
                    break;

            }
        } else {
            # Delete PNG file if needed
            if ($delete_png) {
                unlink($png_file);
            }

            return $target;
        }
    }

    /**
     * Upload the image to WWW_ROOT/files/gallery/{album_id}/picture.jpg
     * Optionaly save it to database
     *
     * @param $path
     * @param $album_id
     * @param $filename
     * @param $filesize
     * @param $tmp_name
     * @param int $width
     * @param int $height
     * @param $action
     * @param bool $save
     * @param null $main_id
     * @param string $style
     * @return mixed|null
     * @throws ForbiddenException
     */
    public function uploadFile(
        $path = null,
        $album_id = null,
        $filename,
        $tmp_name = null,
        $width = 0,
        $height = 0,
        $action,
        $save = false,
        $main_id = null,
        $style = 'full'
    ) {

        if (!$album_id) {
            throw new ForbiddenException("The album ID is required");
        }

        if (!$path) {
            throw new ForbiddenException("The PATH is required");
        }

        if (!$tmp_name) {
            throw new ForbiddenException("The TMP_NAME is required");
        }

        # Copy the file to the folder
        if (copy($tmp_name, $path)) {

            # Resize only if the width or the height has benn informed
            if (!!$width || !!$height) {
                # Image transformation / Manipulation

                try {
                    $path = $this->resizeCrop($path, $width, $height, $action);
                } catch (InternalErrorException $e) {
                    throw new ForbiddenException($e->getMessage());
                }
            }

            if ($save) {
                return $this->savePicture($album_id, $filename, $path, $main_id, $style);
            }

            return null;
        } else {
            throw new ForbiddenException("Upload failed. Check your folders permissions.");
        }
    }

    /**
     * Create extra images from a original one bases on
     * styles defined on bootstrap.php
     *
     * @param $styles
     * @param $filename
     * @param $filesize
     * @param $tmp_name
     * @param $album_id
     * @param $main_id
     * @param $image_name
     * @throws ForbiddenException
     */
    public function createExtraImages($styles, $filename, $tmp_name, $album_id, $main_id, $image_name)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (count($styles)) {
            foreach ($styles as $style_name => $style) {
                $width = $style[0];
                $height = $style[1];
                $crop = $style[2] ? "crop" : "";

                # eg: medium-1982318927313.jpg
                $custom_filename = $style_name . '-' . $image_name . '.' . $ext;

                $path = $this->generateFilePath($album_id, $custom_filename);

                try {
                    $this->uploadFile(
                        $path,
                        $album_id,
                        $style_name . '-' . $filename,
                        $tmp_name,
                        $width,
                        $height,
                        $crop,
                        true,
                        $main_id,
                        $style_name
                    );
                } catch (ForbiddenException $e) {
                    throw new ForbiddenException($e->getMessage());
                }
            }
        }
    }

    /**
     * @param null $picture_id
     * @return array
     */
    private function getChild($picture_id = null)
    {
        $this->unbindModel(
            array('belongsTo' => array('Gallery.Album'))
        );

        $childrens = $this->find(
            'all',
            array(
                'conditions' => array(
                    'main_id' => $picture_id
                ),
                'fields' => array('Picture.path', 'Picture.id', 'Picture.style')
            )
        );

        $childs = array();
        foreach ($childrens as $child) {
            $childs[$child['Picture']['style']] = $child['Picture']['link'];
        }

        return $childs;
    }

    /**
     * Get file size
     * @param null $path
     * @return integer
     */
    public function getFileSize($path = null)
    {
        $file = new File($path);
        return $file->size();
    }

    /**
     * @param $album_id
     * @param $filename
     * @return string
     */
    public function generateFilePath($album_id = null, $filename = null)
    {
        if (!$album_id || !$filename) {
            return false;
        }

        return WWW_ROOT . 'files' . DS . 'gallery' . DS . $album_id . DS . $filename;
    }
}

?>
