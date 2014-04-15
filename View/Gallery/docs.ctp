<link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/default.min.css">
<script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<div class="row">
	<div class="col-md-3">
		<div data-spy="affix" data-offset-top="60" data-offset-bottom="100">
			<ul class="nav nav-pills nav-stacked">
				<li>
					<a href="#installing-gallery">Installing Gallery</a>
				</li>
				<li>
					<a href="#attach-gallery-model">How to attach a gallery to a model?</a>
				</li>
				<li>
					<a href="#how-to-create-a-new-gallery">How to create a new Gallery?</a>
				</li>
				<li>
					<a href="#how-to-create-a-non-relation-gallery">How to create a standalone gallery?</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-md-9">

		<div>
			<h3>What is this plugin?</h3>
			<p>Gallery lets you create multiple galleries or albums for any model in your CakePHP App. With Gallery you can
				manipulate your photos in many ways as cropping, resizing, renaming and many more. </p>
		</div>


		<hr/>

		<div id="installing-gallery">
			<h3>Installing Gallery</h3>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid corporis doloremque eveniet fugiat, id in iste pariatur quis. Aliquam explicabo ipsa maiores nihil porro quisquam similique ullam vel. Dolores, molestias.</p>
		</div>

		<hr/>
		<div id="attach-gallery-model">
			<h3>How to attach a gallery to a model?</h3>
			<p>Integrating Gallery with a model of your application is very simple and takes only seconds, and the best is you
				do not need to change your database. To begin open the model you want to attach a gallery, in this example will
				be <i><strong>Product.php</strong></i></p>

<pre><code>Class Product extends AppModel{
		public $name = 'Product';
		}</code></pre>

			<p>Now you just need to add the $actsAs attribute in your model: </p>
<pre><code>Class Product extends AppModel{
		public $name = 'Product';
		public $actsAs = 'Gallery.Gallery';
		}</code></pre>

			<p>And its <strong>done!</strong> To list all categories attached to a record, you can do something like this: </p>
<pre><code>$this->Product->id = 10;
		/** List of all pictures in this product **/
		$this->Product->getGallery();</code></pre>

		</div>

		<hr/>

		<div id="how-to-create-a-new-gallery">
			<h3>How to create a new gallery?</h3>
			<p>Every record that have Gallery attached at it already have 1 gallery to start uploading files. To link to a record gallery is quite simple:</p>
			<p>Using the CakePHP Html helper:</p>
			<pre><code>echo $this->Html->link('New gallery', array(
					'controller' => 'gallery',
					'action' => 'upload',
					'model' => 'product',
					'model_id' => $product_id
					));</code></pre>
			<p>If you don't want to use the Html helper you can link to this pattern: <i><strong>/your_app/gallery/upload/{model}/{model_id}</strong></i></p>
		</div>

		<hr/>

		<div id="how-to-create-a-non-relation-gallery">
			<h3>How to create a standalone gallery?</h3>
			<p>You can create a gallery that don't belongs to any model, a standalone gallery. To create one of those you will use the same example as above,
				but passing the model and the model_id as NULL: </p>
<pre><code>echo $this->Html->link('New gallery', array(
		'controller' => 'gallery',
		'action' => 'upload',
		'model' => null,
		'model_id' => null
		));</code></pre>
		</div>

	</div>
</div>