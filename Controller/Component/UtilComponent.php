<?php

class UtilComponent extends Component
{
    public function getToken($length = 32)
    {
        return substr(md5(uniqid(mt_rand(), true)) , 0, 32);
    }

    public static function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function checkFileErrors($file)
    {
        if (!$file['error'] == 0) {
            throw new ForbiddenException("Upload failed. Check your file.");
        }
    }

    /**
     * Validate file extension
     * @param $ext
     * @throws ForbiddenException
     */
    public function validateExtensions($ext)
    {
        if (!in_array(strtolower($ext), Configure::read('GalleryOptions.File.allowed_extensions'))) {
            throw new ForbiddenException("You cant upload this kind of file.");
        }
    }
}

?>
