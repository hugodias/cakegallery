<?php $this->Html->css(array(
    'Gallery.style'
),
    array('block' => 'css')) ?>

<?php
if (Configure::read('GalleryOptions.App.interfaced'))
    $this->Html->script('Gallery.interface', array('block' => 'js'));
?>

<div class="row">
    <div class="col-md-10">
        <h2><?php echo $album['Album']['title'] ?></h2>
    </div>
    <div class="col-md-2">
        <?php echo $this->Html->link(
            '<i class="fa fa-edit"></i> ' . __d('gallery', 'Edit album'),
            array(
                'controller' => 'albums',
                'action' => 'upload',
                'gallery_id' => $album['Album']['id']
            ),
            array(
                'class' => 'btn btn-primary btn-sm pull-right',
                'style' => 'margin-top: 20px',
                'escape' => false
            )
        );
        ?>
    </div>
</div>

<hr/>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <?php echo $this->Gallery->showroomTmpl($album) ?>
        </div>
    </div>
</div>
