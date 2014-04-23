<div class="row">
  <div class="col-md-12">
    <div id="folderStatus" class="alert alert-success hide">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-3">
    <h3><i class="fa fa-folder"></i> <?php echo !empty($folder) ? $folder['Folder']['title'] : 'New gallery' ?></h3>
    <hr/>


    <?php
    $data = $this->Js->get('#FolderUpdateForm')->serializeForm(array('isForm' => true, 'inline' => true));
    $this->Js->get('#FolderUpdateForm')->event(
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
    echo $this->Form->create('Gallery.Folder', array('action' => 'update', 'default' => false));
    ?>
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">
          <i class="fa fa-cogs"></i>
          Customize folder options
        </h3>
      </div>
      <div class="panel-body">
        <?php if (!empty($folder)) { ?>
          <?php echo $this->Form->input('id', array('value' => $folder['Folder']['id'])) ?>
        <?php } ?>

        <?php echo $this->Form->input('title', array('type' => 'hidden', 'value' => !empty($folder['Folder']['title']) ? $folder['Folder']['title'] : '')) ?>
        <div class="row">
          <div class="col-lg-12">
            <?php echo $this->Form->input(
              'default_name',
              array(
                'value' => !empty($folder) ? $folder['Folder']['default_name'] : '',
                'label' => 'New base name for all images',
                'placeholder' => 'Ex: xbox-360')) ?>

            <?php echo $this->Form->input('width', array(
              'value' => !empty($folder) ? $folder['Folder']['width'] : '',
              'label' => 'Width (only numbers)',
              'placeholder' => 'Ex: 800')) ?>

            <?php echo $this->Form->input('height', array(
              'value' => !empty($folder) ? $folder['Folder']['height'] : '',
              'label' => 'Height (only numbers)',
              'placeholder' => 'Ex: 600')) ?>
            <hr/>

            <h4>Image manipulation</h4>

            <div class="manipulation">
            <?php echo $this->Form->input(
              'action',
              array(
                'type' => 'radio',
                'value' => !empty($folder) ? $folder['Folder']['action'] : 'proportional_resize',
                'legend' => false,
                'separator' => '<div class="clearfix"></div>',
                'options' => array(
                  'crop' => 'Crop',
                  'fill' => 'Fill',
                  'proportional_resize' => 'Proportional resize',
                  '' => 'Force resize'
                )

              ))?>
            </div>
            <hr/>
            <h4>Thumbnails</h4>
            <div class="manipulation">
              <?php echo $this->Form->input('th', array(
                'type' => 'checkbox',
                'value' => 'Y',
                'checked' => !empty($folder['Folder']['th']) && $folder['Folder']['th'] == 'Y' ? true : false,
                'label' => 'Generate Thumbnail'))?>
            </div>


            <?php echo $this->Form->input('th_width', array(
              'value' => !empty($folder) ? $folder['Folder']['th_width'] : '',
              'label' => 'Thumbnail width (only numbers)',
              'placeholder' => 'Ex: 180')) ?>
            <?php echo $this->Form->input('th_height', array(
              'value' => !empty($folder) ? $folder['Folder']['th_height'] : '',
              'label' => 'Thumbnail height (only numbers)',
              'placeholder' => 'Ex: 180'
            )) ?>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <button class="btn btn-success btn-block">
          <i class="fa fa-check"></i>
          Save preferences
        </button>
      </div>
    </div>
    </form>
    <?php echo $this->Js->writeBuffer(); ?>
    <hr/>
  </div>
  <div class="col-lg-9">
    <h3>
      <i class="fa fa-picture-o"></i>
      Manage your images
    </h3>

    <hr/>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> Galeries', array('controller' => 'gallery', 'action' => 'index'), array('class' => 'btn btn-default btn-sm pull-left', 'escape' => false))?>
    <?php echo $this->Html->link('<i class="fa fa-trash-o"></i> Delete all', '', array('escape' => false, 'class' => 'btn btn-danger btn-sm pull-right', 'style' => 'margin-left: 10px')) ?>
    <?php echo $this->Html->link('<i class="fa fa-cloud-download"></i> Download zip', '', array('escape' => false, 'class' => 'btn btn-info btn-sm pull-right')) ?>
    <div class="clearfix"></div>
    <hr/>
    <?php echo $this->Form->create(null, array(
      'url' => array(
        'plugin' => 'gallery',
        'controller' => 'records',
        'action' => 'add'),
      'class' => 'dropzone',
      'id' => 'drop'
    ))?>
    </form>
    <div class="clearfix"></div>

  </div>
</div>


<div id="folderinfo"
     data-public-folder-path="<?php echo $this->params->webroot . "files/gallery/" . $folder['Folder']['id'] . "/" ?>"></div>

<script>

  $(function () {

    var myDropzone = new Dropzone("#drop");

    myDropzone.on("sending", function (file, xhr, formData) {
      var folder_id = $('#FolderId').val();
      formData.append("folder_id", folder_id);
    });

    <?php foreach($files as $f){ ?>
    var mockFile = { name: "<?php echo $f['name']?>", size: <?php echo $f['size']?> };
    myDropzone.emit("addedfile", mockFile);
    myDropzone.emit("thumbnail", mockFile, "<?php echo $this->params->webroot.'files/gallery/'.$folder['Folder']['id'].'/'.$f['name']?>");
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
