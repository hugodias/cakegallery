<div class="row">
	<div class="col-md-12">
		<div id="folderStatus" class="alert alert-success hide">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-3">

		<?php
		$data = $this->Js->get('#AlbumUpdateForm')->serializeForm(array('isForm' => true, 'inline' => true));
		$this->Js->get('#AlbumUpdateForm')->event(
			'submit',
			$this->Js->request(
				array('action' => 'update'),
				array(
					'update' => '#folderStatus',
					'data' => $data,
					'async' => true,
					'dataExpression' => true,
					'method' => 'POST',
					'complete' => '$(".alert-success").removeClass("hide"); window.setTimeout(function(){$(".alert-success").addClass("hide")}, 2000);'
				)
			)
		);
		echo $this->Form->create('Gallery.Album', array('action' => 'update', 'default' => false));
		?>
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-cogs"></i>
					Customize gallery options
				</h3>
			</div>
			<div class="panel-body">
				<?php if (!empty($album)) { ?>
					<?php echo $this->Form->input('id', array('value' => $album['Album']['id'])) ?>
				<?php } ?>
				<div class="row">
					<div class="col-lg-12">
						<?php echo $this->Form->input(
							'title',
							array(
								'value' => !empty($album) ? $album['Album']['title'] : '',
								'label' => 'Album title',
								'placeholder' => 'Ex: xbox-360')) ?>

						<hr/>

						<h4>Status</h4>

						<div class="manipulation">
							<?php echo $this->Form->input(
								'status',
								array(
									'type' => 'radio',
									'value' => !empty($album) ? $album['Album']['status'] : 'published',
									'legend' => false,
									'separator' => '<div class="clearfix"></div>',
									'options' => array(
										'draft' => 'Draft',
										'published' => 'Published'
									)

								))?>
						</div>

						<hr/>

						<?php echo $this->Form->input('tags', array(
							'value' => !empty($album) ? $album['Album']['tags'] : '',
							'label' => 'Tags (comma separated)',
							'placeholder' => 'Ex: city, sun, chicago')) ?>


					</div>
				</div>
			</div>
			<div class="panel-footer">
				<button class="btn btn-success btn-block">
					<i class="fa fa-check"></i>
					Save
				</button>
			</div>
		</div>
		</form>
		<?php echo $this->Js->writeBuffer(); ?>
		<hr/>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-7">
				<h3>
					<i class="fa fa-picture-o"></i>
					Album images
				</h3>
			</div>
			<div class="col-md-5" style="padding-top: 20px">
				<?php echo $this->Html->link(
					'<i class="fa fa-trash-o"></i> Delete album',
					array(
						'controller' => 'albums',
						'action' => 'delete',
						'plugin' => 'gallery',
						$album['Album']['id']
					),
					array(
						'escape' => false,
						'class' => 'btn btn-danger btn-sm pull-right confirm-delete',
						'style' => 'margin-left: 10px'
					)
				); ?>
				<?php echo $this->Html->link(
					'<i class="fa fa-cloud-upload"></i> Upload pictures',
					'#modalUpload',
					array(
						'data-toggle' => 'modal',
						'escape' => false,
						'class' => 'btn btn-success btn-sm pull-right'
					)
				); ?>
			</div>
		</div>


		<hr/>

		<div id="container-pictures">
			<?php if (!count($album['Picture'])) { ?>
				<div class="container-empty">
					<div class="img"><i class="fa fa-picture-o"></i></div>
					<h2>This album don't have any picture yet.</h2>
					<br/>
					<a href="#modalUpload" data-toggle="modal" class="btn btn-success">
						<i class="fa fa-cloud-upload"></i>
						Upload images
					</a>
				</div>
			<?php } else { ?>
				<?php foreach ($files as $f) { ?>

				<?php } ?>
				<div class="row list-unstyled">


					<?php foreach ($files as $picture) { ?>
						<div class="col-xs-3">
							<div class="thumbnail th-pictures-container" style="position: relative">
								<?php $picture_url = !empty($picture['link']) ? $picture['link'] : "http://placehold.it/255x170"; ?>
								<img src="<?php echo $picture_url ?>" alt="">

								<div class="icons-manage-image">
									<a href="javascript:void(0)" class="remove-picture btn btn-lg btn-danger"
									   data-file-id="<?php echo $picture['id'] ?>">
										<i class="fa fa-trash-o"></i>
									</a>
								</div>
							</div>
						</div>
					<?php } ?>


				</div>
			<?php } ?>
		</div>

	</div>
</div>


<div id="folderinfo"
     data-public-folder-path="<?php echo $this->params->webroot . "files/gallery/" . $album['Album']['id'] . "/" ?>"></div>

<script>

	$(function () {

		var myDropzone = new Dropzone("#drop");

		myDropzone.on("sending", function (file, xhr, formData) {
			var album_id = $('#AlbumId').val();
			formData.append("album_id", album_id);
		});

		<?php foreach($files as $f){ ?>
		<!--		var mockFile = { id: -->
		<?php //echo $f['id']?><!--, name: "-->
		<?php //echo $f['name']?><!--", size: -->
		<?php //echo $f['size']?><!-- };-->
		<!--		myDropzone.emit("addedfile", mockFile);-->
		<!--		myDropzone.emit("thumbnail", mockFile, "-->
		<?php //echo $f['link']?><!--");-->
		<?php } ?>

		$('.panel-heading, .close-config').bind('click', function () {
			$('.panel-body, .panel-footer').slideToggle(300);
		})
	})
</script>


<div class="modal fade" id="modalViewPicture" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pictureName"></h4>
			</div>
			<div class="modal-body">
				<img src="" alt="" class="img-preview-full" width="100%"/>
			</div>
			<div class="modal-footer">

			</div>
			</form>
		</div>
	</div>
</div>


<div class="modal fade modal-upload" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pictureName">
					<i class="fa fa-picture-o"></i>
					Upload pictures
				</h4>
			</div>
			<div class="modal-body">
				<?php echo $this->Form->create(null, array(
					'url' => array(
						'plugin' => 'gallery',
						'controller' => 'pictures',
						'action' => 'upload'),
					'class' => 'dropzone',
					'id' => 'drop'
				))?>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">
					<i class="fa fa-check"></i>
					Done
				</button>
			</div>
			</form>
		</div>
	</div>
</div>
