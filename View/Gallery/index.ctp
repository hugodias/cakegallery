<?php echo $this->Gallery->new_gallery_button(array('class' => 'btn btn-primary pull-left')); ?>

<div class="row">
	<div class="col-md-12">
		<h3>Galleries</h3>

		<div class="row">
			<?php foreach ($galleries as $gallery) { ?>
				<a
					href="<?php echo $this->Html->url(array('controller' => 'albums', 'action' => 'upload', 'plugin' => 'gallery', 'gallery_id' => $gallery['Album']['id'])) ?>">
					<div class="col-sm-6 col-md-3">
						<div class="thumbnail">
							<?php $picture_url = !empty($gallery['Picture'][0]['styles']['medium']) ? $gallery['Picture'][0]['styles']['medium'] : "http://placehold.it/255x170"; ?>
							<img src="<?php echo $picture_url ?>" alt="...">

							<div class="caption">
								<h4><?php echo $gallery['Album']['title'] ?></h4>
							</div>
						</div>
					</div>
				</a>
			<?php } ?>
		</div>
	</div>
</div>