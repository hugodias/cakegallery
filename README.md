Cake Gallery
=========

CakeGallery is a cakephp plugin to manage galleries, albums and pictures

![Album page](https://docs.google.com/file/d/0B_Jfjn30EDVzaW14UllSZXZQeWs/preview)

DEMO: [http://galleryopenshift-cakeupload.rhcloud.com/gallery](http://galleryopenshift-cakeupload.rhcloud.com/gallery)

DEMO2 (Video): [https://www.youtube.com/watch?v=AhU16ji_i9g](https://www.youtube.com/watch?v=AhU16ji_i9g)

With CakeGallery you can:

* Create Albums
* Add tags, title and status (published or drafts) to albums
* Upload multiple pictures at the same time
* Create multiple versions for your pictures (thumbnails, crop, rezise, etc)
* Integrate any album with any other Model in your application

---

## Requirements
To use CakeGallery you need the following requirements

* CakePHP 2.x application
* PHP 5.3+ (bundled GD 2.0.28+ for image manipulation)
* MySQL
* Apache

---

## Version
1.1.2

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
### Features

---

#### How to attach a gallery to a model?

Integrating Gallery with a model of your application is very simple and takes only seconds, and the best is you do not need to change your database. To begin open the model you want to attach a gallery, in this example will be `Product.php`

```php
Class Product extends AppModel{
    public $name = 'Product';
}
```		
Now you just need to add the $actsAs attribute in your model:

```php
Class Product extends AppModel{
	public $name = 'Product';
	public $actsAs = 'Gallery.Gallery';
}
```
And its done! To list all galleries attached to a Product, you can do something like this:

```php
$this->Product->id = 10;
$this->Product->getGallery();
```

---

#### How to create a new gallery attached with a model?

Every Picture that have Gallery attached at it already have 1 gallery to start uploading files. To link to a Picture gallery is quite simple:<br/> Using the CakePHP Html helper:

```php
echo $this->Html->link('New gallery', array(
		'controller' => 'gallery',
		'action' => 'upload',
		'plugin' => 'gallery',
		'model' => 'product',
		'model_id' => $product_id
		));
```
If you don't want to use the Html helper you can link to this pattern: `/your_app/gallery/upload/{model}/{model_id}`

---

#### How to create a standalone gallery? (Non-related gallery)

You can create a gallery that don't belongs to any model, a standalone gallery. To create one of those you will use the same example as above, but passing the model and the model_id as NULL:

```php
echo $this->Html->link('New gallery', array(
		'controller' => 'gallery',
		'action' => 'upload',
		'plugin' => 'gallery',
		'model' => null,
		'model_id' => null
		));
```

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
You can create more styles on styles array of modify the default size of the defaults

PS: don't modify the default names as **medium** or **small**. This files are used by the plugin.

