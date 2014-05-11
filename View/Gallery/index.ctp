<div class="row">
	<div class="col-md-10">
		<h2><?php echo $page_title ?></h2>
	</div>
	<div class="col-md-2">
		<?php echo $this->Gallery->new_gallery_button(array('class' => 'btn btn-primary pull-right', 'style' => 'margin-top: 10px')); ?>
	</div>
</div>

<hr/>

<div class="row">
	<div class="col-md-12">
		<div class="row">
			<?php foreach ($galleries as $gallery) { ?>
				<a
					href="<?php echo $this->Html->url(array('controller' => 'albums', 'action' => 'upload', 'plugin' => 'gallery', 'gallery_id' => $gallery['Album']['id'])) ?>">
					<div class="col-sm-6 col-md-3">
						<div class="thumbnail <?php echo $search_status?>">
							<?php $picture_url = !empty($gallery['Picture'][0]['styles']['medium']) ? $gallery['Picture'][0]['styles']['medium'] : "http://placehold.it/255x170"; ?>
							<img src="<?php echo $picture_url ?>" alt="...">

							<div class="caption">
								<h4>
									<?php if($gallery['Album']['status'] == 'draft'){ ?>
										<i class="fa fa-pagelines"></i>
									<?php } else {?>
									<?php } ?>
									<?php echo $gallery['Album']['title'] ?>
								</h4>
								<h5><i class="fa fa-calendar"></i> <?php echo $this->Time->format($gallery['Album']['created'], '%B %e, %Y %H:%M %p') ?></h5>
								<h5><i class="fa fa-camera-retro"></i> <?php echo count($gallery['Picture'])?></h5>

							</div>
						</div>
					</div>
				</a>
			<?php } ?>
		</div>
	</div>
</div>

<hr/>

<div class="row">
	<div class="col-md-12">
		<?php if($search_status == "draft"){ ?>
			<?php echo $this->Html->link('<i class="fa fa-check"></i> Published albums', '?status=published', array('class' => 'btn', 'escape' => false))?>
		<?php } else { ?>
			<?php echo $this->Html->link('<i class="fa fa-pagelines"></i> Drafts', '?status=draft', array('class' => 'btn', 'escape' => false))?>
		<?php } ?>
	</div>
</div>