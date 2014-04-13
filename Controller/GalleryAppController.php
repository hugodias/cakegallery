<?php
App::uses('Controller', 'Controller');

class GalleryAppController extends AppController {

	public function beforeFilter() {
		if (!$this->_checkConfigFile()) {
			# Set default theme for app
			$default_options = array(
				'App' => array(
					'theme' => 'superhero'
				)
			);
			Configure::write('GalleryOptions', $default_options);

			$this->render('Gallery.Install/config');
		}
	}

	/**
	 * Check if plugin config file exists
	 * @return bool
	 */
	private function _checkConfigFile() {
		return !!file_exists(App::pluginPath('Gallery') . 'Config' . DS . 'config.php');
	}

} 