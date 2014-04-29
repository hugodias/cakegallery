<?php echo $this->Gallery->button('product',2) ?>

<?php echo $this->Gallery->new_gallery_button(); ?>

<div class="row">
	<div class="col-md-12">
		<h3>Galleries</h3>
		<div class="row">
			<?php debug($galleries)?>
			<?php foreach($galleries as $gallery){?>
				<div class="col-sm-6 col-md-4">
					<div class="thumbnail">
						<img src="<?php echo $gallery['Picture'][0]['link']?>" alt="...">
						<div class="caption">
							<h3><?php echo $gallery['Album']['title']?></h3>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>