<?php
App::uses('File', 'Utility');
App::uses('ConnectionManager', 'Model');

class InstallController extends GalleryAppController
{


    public function configuration_form()
    {
        $this->render('Gallery.Install/config');
    }

    /**
     * Configure galleries and create mysql tables
     */
    public function configure()
    {
        $files_path = WWW_ROOT . 'files/';
        if (!file_exists($files_path)) {
            mkdir($files_path, 0755);
        }
        $galleries_path = $files_path . 'gallery/';
        if (!file_exists($galleries_path)) {
            mkdir($galleries_path, 0755);
        }

        $this->_configureDatabase();

        $this->Session->setFlash('Success! Gallery is now installed in your app.');

        $this->redirect(
            array(
                'controller' => 'gallery',
                'action' => 'index',
                'plugin' => 'gallery'
            )
        );

        $this->render(false, false);
    }

    /**
     * Configure database to use this plugin
     */
    private function _configureDatabase()
    {
        try {
            $db = ConnectionManager::getDataSource('default');

            if (!$db->isConnected()) {
                throw new Exception("You need to connect to a MySQL Database to use this Plugin.");
            }

            /** Verify if the tables already exists */
            if (!$this->_checkTables($db->listSources())) {
                $this->_setupDatabase($db);
            }

            # Create config file
            $this->_createConfigFile();

            return;

        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage());
            $this->redirect('/');
        }
    }

    /**
     * @param $tables
     * @return bool
     */
    private function _checkTables($tables)
    {
        return !!in_array('gallery_albums', $tables) && !!in_array('gallery_pictures', $tables);
    }

    /**
     * Execute Config/cakegallery.sql to create the tables
     * Create the config File
     * @param $db
     */
    private function _setupDatabase($db)
    {
        # Execute the SQL to create the tables
        $sqlFile = new File(App::pluginPath('Gallery') . 'Config' . DS . 'cakegallery.sql', false);
        $db->rawQuery($sqlFile->read());
        $sqlFile->close();
    }

    /**
     * Create the config file copying the config.php.install file
     */
    private function _createConfigFile()
    {
        $destinationPath = App::pluginPath('Gallery') . 'config' . DS . 'config.php';

        if (!file_exists($destinationPath)) {
            $configFile = new File(App::pluginPath('Gallery') . 'config' . DS . 'config.php.install');

            $configFile->copy($destinationPath);
        }
    }
} 