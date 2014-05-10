<link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/default.min.css">
<script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<div class="row">
	<div class="col-md-12">
		<h1>Cake Gallery</h1>

		<p>A cakephp plugin to manage galleries, albums and pictures</p>
		<br/>

		<h3>What is this plugin?</h3>

		<p><br/>
			Gallery lets you create multiple galleries or albums for any model in your CakePHP App. With Gallery you can
			manipulate your photos in many ways as cropping, resizing, renaming and many more.</p>

		<p>With CakeGallery you can:<br/><br/>
			- Create Albums<br/>
			- Add tags, title and status (published or drafts) to albums<br/>
			- Upload multiple pictures at the same time<br/>
			- Create multiple versions for your pictures (thumbnails, crop, rezise, etc)<br/>
			- Integrate any album with any other Model in your application</p>
		<hr>
		<h2>Requirements</h2>

		<p>To use CakeGallery you need the following requirements<br/>
			- CakePHP 2.x application<br/>
			- PHP 5.3+ (bundled GD 2.0.28+ for image manipulation)<br/>
			- MySQL<br/>
			- Apache</p>
		<hr>
		<h2>Version</h2>

		<p>1.0</p>
		<hr>
		<h3>Before start</h3>
		<ul>
			<li>Make sure that your <code>app/webroot/files</code> folder is writtable</li>
		</ul>
		<hr>
		<h3>Wizzard Installation (recommended)</h3>
		<ul>
			<li>Download and unzip the files you downloaded from CodeCanyon</li>
			<li>Copy the <code>Gallery</code> folder to your app plugins folder: <code>app/Plugin/</code></li>
			<li>Make sure that your <code>app/Plugin/Gallery/Config</code> folder is writtable (For installation only)</li>
			<li>Open your <code>app/Config/bootstrap.php</code> file and add the following code</li>
		</ul>
<pre><code class="lang-php">CakePlugin::load(array(
	&#39;Gallery&#39; =&gt; array(
	&#39;bootstrap&#39; =&gt; true,
	&#39;routes&#39; =&gt; true
)));</code></pre>
		<ul>
			<li>To finish the installation go to your browser and type <code>http://your-app-url/gallery</code> and follow the
				wizzard
			</li>
		</ul>
		<hr>
		<br/>

		<h3>Manual installation</h3>


		<ul>
			<li>Download and unzip the files you downloaded from CodeCanyon</li>
			<li>Copy the <code>Gallery</code> folder to your app plugins folder: <code>app/Plugin/</code></li>
			<li>Rename the <code>app/Plugin/Gallery/Config/config.php.install</code> file to <strong>config.php</strong></li>
			<li>Import the SQL file <code>app/Plugin/Gallery/Config/cakegallery.sql</code> to your database</li>
			<li>Open your <code>app/Config/bootstrap.php</code> file and add the following code<br/><br/></li>
			<li><pre><code class="lang-php">CakePlugin::load(array(
	&#39;Gallery&#39; =&gt; array(
	&#39;bootstrap&#39; =&gt; true,
	&#39;routes&#39; =&gt; true
)));</code></pre>
			</li>
			<li>Create a <strong>gallery</strong> folder inside <code>app/webroot/files</code> and give it writable permissions. (<code>app/webroot/files/gallery</code>)</li>
		</ul>

		<ul>
			<li>Check at <code>http://your-app-url/gallery</code> to see your plugin working.
			</li>
		</ul>
		<hr>
		<h2>Features</h2>
		<hr>
		<h3>How to attach a gallery to a model?</h3><br/>

		<p>Integrating Gallery with a model of your application is very simple and takes only seconds, and the best is you
			do not need to change your database. To begin open the model you want to attach a gallery, in this example will be
			<strong>Product.php</strong></p>
<pre><code class="lang-php">Class Product extends AppModel{
		public $name = &#39;Product&#39;;
		}</code></pre>
		<p>Now you just need to add the $actsAs attribute in your model:</p>
<pre><code class="lang-php">Class Product extends AppModel{
		public $name = &#39;Product&#39;;
		public $actsAs = &#39;Gallery.Gallery&#39;;
		}</code></pre>
		<p>And its <strong>done!</strong> To list all galleries attached to a Product, you can do something like this:</p>
<pre><code class="lang-php">$this-&gt;Product-&gt;id = 10;
		$this-&gt;Product-&gt;getGallery();</code></pre>
		<br/><br/>
		<hr>

		<h3>How to create a new gallery attached with a model?</h3><br/>

		<p>Every Picture that have Gallery attached at it already have 1 gallery to start uploading files. To link to a
			Picture gallery is quite simple:&lt;br/&gt;
			Using the CakePHP Html helper:</p>
<pre><code class="lang-php">echo $this-&gt;Html-&gt;link(&#39;New gallery&#39;, array(
		&#39;controller&#39; =&gt; &#39;gallery&#39;,
		&#39;action&#39; =&gt; &#39;upload&#39;,
		&#39;plugin&#39; =&gt; &#39;gallery&#39;,
		&#39;model&#39; =&gt; &#39;product&#39;,
		&#39;model_id&#39; =&gt; $product_id
		));</code></pre>
		<p>If you don&#39;t want to use the Html helper you can link to this pattern: <strong>/your_app/gallery/upload/{model}/{model_id}</strong>
		</p>
		<br/>
		<hr>

		<h3>How to create a standalone gallery? (Non-related gallery)</h3>

		<p>&lt;br/&gt;
			You can create a gallery that don&#39;t belongs to any model, a standalone gallery. To create one of those you
			will use the same example as above, but passing the model and the model_id as NULL:</p>
<pre><code class="lang-php">echo $this-&gt;Html-&gt;link(&#39;New gallery&#39;, array(
		&#39;controller&#39; =&gt; &#39;gallery&#39;,
		&#39;action&#39; =&gt; &#39;upload&#39;,
		&#39;plugin&#39; =&gt; &#39;gallery&#39;,
		&#39;model&#39; =&gt; null,
		&#39;model_id&#39; =&gt; null
		));</code></pre>

		<hr/>

		<h3>How to change image resize dimensions?</h3>
		<p>All configuration related to images you can find at <code>app/Plugin/Gallery/Config/bootstrap.php</code></p>
<pre><code class="lang-php">$config = array(
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
	Configure::write('GalleryOptions', $config);	</code></pre>

		<p>You can create more styles on <code>styles</code> array of modify the default size of the defaults</p>
		<p><i><strong>PS:</strong> don't modify the default <u>names</u> as <i>medium</i> or <i>small</i>. This files are used by the plugin.</i></p>
	</div>
</div>