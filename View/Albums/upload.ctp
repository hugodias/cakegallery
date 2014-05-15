<div class="row">
    <div class="col-md-12">
        <div id="folderStatus" class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
    </div>
</div>
<div class="row">
<div class="col-md-12">

<div class="panel panel-default panel-container-album">
    <div class="panel-heading">
        <h2 class="panel-title pull-left">
            <i class="fa fa-picture-o"></i>
            <?php echo $album['Album']['title'] ?>
        </h2>

        <?php echo $this->Html->link(
            '<i class="fa fa-cloud-upload"></i> Upload pictures',
            '#modalUpload',
            array(
                'data-toggle' => 'modal',
                'escape' => false,
                'class' => 'btn btn-success btn-sm pull-right'
            )
        ); ?>
        <?php echo $this->Html->link(
            '<i class="fa fa-external-link"></i> View album',
            array(
                'controller' => 'albums',
                'action' => 'view',
                $album['Album']['id'],
                'plugin' => 'gallery'
            ),
            array(
                'data-toggle' => 'modal',
                'escape' => false,
                'target' => '_blank',
                'class' => 'btn btn-info btn-sm pull-right',
                'style' => 'margin-right: 10px; margin-left: 10px'
            )
        ); ?>

        <a href="javascript:void(0)" class="btn btn-sm btn-primary open-config pull-right">
            <i class="fa fa-cog"></i> Options
        </a>

        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                $data = $this->Js->get('#AlbumUpdateForm')->serializeForm(
                    array('isForm' => true, 'inline' => true)
                );
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
                            'complete' => '$(".alert-success").fadeIn(600); window.setTimeout(function(){$(".alert-success").fadeOut(400)}, 2000); '
                        )
                    )
                );
                echo $this->Form->create('Gallery.Album', array('action' => 'update', 'default' => false));
                ?>
                <div class="panel panel-success options">
                    <div class="panel-heading options">
                        <h3 class="panel-title">
                            <i class="fa fa-cog"></i>
                            Album options
                        </h3>
                    </div>
                    <div class="panel-body options">
                        <?php if (!empty($album)) { ?>
                            <?php echo $this->Form->input('id', array('value' => $album['Album']['id'])) ?>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo $this->Form->input(
                                    'title',
                                    array(
                                        'value' => !empty($album) ? $album['Album']['title'] : '',
                                        'label' => 'Album title',
                                        'placeholder' => 'Ex: xbox-360'
                                    )
                                ) ?>

                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->input(
                                    'tags',
                                    array(
                                        'value' => !empty($album) ? $album['Album']['tags'] : '',
                                        'label' => 'Tags (comma separated)',
                                        'placeholder' => 'Ex: city, sun, chicago'
                                    )
                                ) ?>
                            </div>
                            <div class="col-md-3">
                                <label for="">Status</label>

                                <div class="manipulation">
                                    <?php echo $this->Form->input(
                                        'status',
                                        array(
                                            'type' => 'radio',
                                            'value' => !empty($album) ? $album['Album']['status'] : 'published',
                                            'legend' => false,
                                            'separator' => '',
                                            'options' => array(
                                                'draft' => 'Draft',
                                                'published' => 'Published'
                                            )

                                        )
                                    )?>
                                </div>


                            </div>


                        </div>
                    </div>
                    <div class="panel-footer options">

                        <button class="btn btn-success pull-left btn-sm">
                            <i class="fa fa-check"></i>
                            Save
                        </button>
                        <a href="javascript:void(0)" class="btn btn-default btn-sm pull-left close-config"
                           style="margin-left: 10px">Close</a>


                        <button type="button" class="btn btn-warning btn-sm pull-right popovertrigger"
                                style="margin-left: 10px"
                                data-container="body" data-toggle="popover" data-placement="left" data-content="<ul>
				<li>Use the top form to update your gallery information, such as name, tags or publish status.</li>
				<li>To upload new images to this album, press the upload button.</li>
				<li>Drag the pictures to reorder your gallery. (Dont worry, this changes are saved automatically)</li>
				<li>If you delete this album, all its images will be deleted as well.</li>
				<li>The first image of the album will be considered as the cover. To change the cover just drag the image you want to mark as a cover at the first position of the grid</li>
				</ul>">
                            <i class="fa fa-info-circle"></i> Help
                        </button>

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
                                'style' => 'text-align: right; color: red',
                                'class' => 'pull-right btn btn-sm confirm-delete'
                            )
                        ); ?>

                        <div class="clearfix"></div>
                    </div>
                </div>
                </form>
                <?php echo $this->Js->writeBuffer(); ?>

            </div>
        </div>
        <div id="container-pictures">
            <?php if (!count($album['Picture'])) { ?>
                <div class="container-empty">
                    <div class="img"><i class="fa fa-picture-o"></i></div>
                    <h2>This album doesn't have pictures yet.</h2>
                    <br/>
                    <a href="#modalUpload" data-toggle="modal" class="btn btn-success">
                        <i class="fa fa-cloud-upload"></i>
                        Upload pictures
                    </a>
                </div>
            <?php } else { ?>
                <div class="row" id="sortable">
                    <?php foreach ($files as $picture) { ?>
                        <div class="col-xs-6 col-md-3 ui-state-default" alt="<?php echo $picture['id'] ?>"
                             id="<?php echo $picture['id'] ?>">
                            <div class="thumbnail th-pictures-container" style="position: relative">
                                <?php $picture_url = !empty($picture['styles']['medium']) ? $picture['styles']['medium'] : "http://placehold.it/255x170"; ?>
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
                <?php echo $this->Form->create(
                    null,
                    array(
                        'url' => array(
                            'plugin' => 'gallery',
                            'controller' => 'pictures',
                            'action' => 'upload'
                        ),
                        'class' => 'dropzone',
                        'id' => 'drop'
                    )
                )?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-check"></i>
                    Done
                </button>
            </div>
        </div>
    </div>
</div>
