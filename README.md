Cake Gallery
=========
[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/hugodias/cakegallery?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

CakeGallery is a cakephp plugin to manage galleries, albums and pictures

![Album page](https://dl.dropboxusercontent.com/u/17997827/Screenshot%202015-01-12%2010.34.55.png)


With CakeGallery you can:

* Create Albums
* Add tags, title and status (published or drafts) to albums
* Upload multiple pictures at the same time
* Create multiple versions for your pictures (thumbnails, crop, rezise, etc)
* Integrate any album with any other Model in your application

---

### Videos and Resources

Installing: [https://www.youtube.com/watch?v=OEgVQQTaWkE](https://www.youtube.com/watch?v=OEgVQQTaWkE) - Portuguese

Features: [https://www.youtube.com/watch?v=kxKRSY4Tdjc](https://www.youtube.com/watch?v=kxKRSY4Tdjc) - Portuguese

DEMO: [http://galleryopenshift-cakeupload.rhcloud.com/gallery](http://galleryopenshift-cakeupload.rhcloud.com/gallery)

DEMO2 (Video): [https://www.youtube.com/watch?v=AhU16ji_i9g](https://www.youtube.com/watch?v=AhU16ji_i9g)

---

### Requirements
To use CakeGallery you need the following requirements

* CakePHP 2.x application
* PHP 5.3+ (bundled GD 2.0.28+ for image manipulation)
* MySQL
* Apache

---

### Version
1.2.1

---

### Before start
* Make sure that your `app/webroot/files` folder is writtable

---

### Wizzard Installation (recommended)
* Clone or Download the Zip file from Github
* Copy the `Gallery` folder to your app plugins folder: `app/Plugin/`
* Make sure that your `app/Plugin/Gallery/Config` folder is writtable (For installation only)
* Open your `app/Config/bootstrap.php` file and add the following code

```php
CakePlugin::loadAll(array(
'Gallery' => array(
    'bootstrap' => true,
    'routes' => true
    )));
```
* To finish the installation go to your browser and type `http://your-app-url/gallery` and follow the wizzard

---

### Manual installation
* Clone or Download the Zip file from Github
* Copy the `Gallery` folder to your app plugins folder: `app/Plugin/`
* Rename the `app/Plugin/Gallery/Config/config.php.install` file to **config.php**
* Import the SQL file `app/Plugin/Gallery/Config/cakegallery.sql` to your database
* Open your `app/Config/bootstrap.php` file and add the following code

```php
CakePlugin::loadAll(array(
'Gallery' => array(
    'bootstrap' => true,
    'routes' => true
    )));
```
* Create a **gallery** folder inside `app/webroot/files` and give it writable permissions. (`app/webroot/files/gallery`)

* Check at `http://your-app-url/gallery` to see your plugin working.

---
### FAQ

---

#### The images are not showing up

If you are using windows , have a chance of the images are not being rendered. This will happen because of windows directory separator. To fix it you can use this solution: [http://stackoverflow.com/a/4095765/708385](http://stackoverflow.com/a/4095765/708385)

#### How to attach a gallery to a model?

Integrating Gallery with a model of your application is very simple and takes only seconds, and the best is you do not need to change your database. To begin open the model you want to attach a gallery, in this example will be `Product.php`

```php
class Product extends AppModel{
    public $name = 'Product';
}
```
Now you just need to add the $actsAs attribute in your model:

```php
class Product extends AppModel{
	public $name = 'Product';
	public $actsAs = array('Gallery.Gallery');
}
```
And its done! Now, when you search for this object in database, its pictures will be automatically retrieved from the plugin:

```php
$product = $this->Product->findById(10);
//
// array(
//   'Product' => array(
//     'id' => '1',
//     'name' => 'My Product',
//     'price' => '29.00'
//   ),
//   'Gallery' => array(
//     'Album' => array(
//       ...
//     ),
//     'Picture' => array(
//       ...
//     ),
//     'numPictures' => (int) 2
//   )
// )
```

If you want to manually call for the pictures you will want to disable the automatic feature and call it yourself:

```php
public $actsAs = array('Gallery.Gallery' => array('automatic' => false));
```

```php
// Anycontroller.php
$this->Product->id = 10;
$this->Product->getGallery();
```

---

#### How to create a new gallery attached with a model?

You should use the Gallery link helper. It is very easy to use.

1. Specify the Gallery helper in your controller
2. Use the gallery link helper passing a model and id

```php
# ProductsController.php
class ProductsController extends AppController {
	public $helpers = array('Gallery.Gallery');
}
```

```php
# app/View/Products/view.ctp
echo $this->Gallery->link('product', 10);
```

---

#### How to create a standalone gallery? (Non-related gallery)

You can create a gallery that don't belongs to any model, a standalone gallery. To create one of those you will use the same example as above, but no arguments are needed

```php
# anyview.ctp
echo $this->Gallery->link();
```

---

#### How to change image resize dimensions?
All configuration related to images you can find at app/Plugin/Gallery/Config/bootstrap.php

```php
$config = array(
	'App' => array(
		# Choose what theme you want to use:
		# You can find all themes at Gallery/webroot/css/themes
		# Use the first name in the file as a parameter, eg: cosmo.min.css -> cosmo
		'theme' => 'cosmo'
	),
	'File' => array(
		# Max size of a file (in megabytes (MB))
		'max_file_size' => '20',

		# What king pictures the user is allowed to upload?
		'allowed_extensions' => array('jpg','png','jpeg','gif')
	),
	'Pictures' => array(
		# Resize original image. If you dont want to resize it, you should set a empty array, E.G: 'resize_to' => array()
		# Default configuration will resize the image to 1024 pixels height (and unlimited width)
		'resize_to' => array(0, 1024, false),

		# Set to TRUE if you want to convert all png files to JPG (reduce significantly image size)
		'png2jpg' => true,

		# Set the JPG quality on each resize.
		# The recommended value is 85 (85% quality)
		'jpg_quality' => 85,


		# List of additional files generated after upload, like thumbnails, banners, etc
		'styles' => array(
			'small' => array(50, 50, true), # 50x50 Cropped
			'medium' => array(255, 170, true), # 255#170 Cropped
			'large' => array(0, 533, false) # 533 pixels height (and unlimited width)
			)
		)
	);
	Configure::write('GalleryOptions', $config);
```

You can create as many styles you want, just add in the styles array, and future versions will be created on uploading.

PS: DO NOT modify the default names as **medium** or **small**. You can safely modify the width, height and action but the names
are used by the plugin, so don't change then.
