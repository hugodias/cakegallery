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
            '<i class="fa fa-edit"></i> Edit album',
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

            <?php if (empty($album['Picture'])) { ?>
                <div class="container-empty">
                    <div class="img"><i class="fa fa-picture-o"></i></div>
                    <h2>This album has no photos yet.</h2>
                </div>
            <?php } else { ?>
                <?php foreach ($album['Picture'] as $picture) { ?>
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <a href="<?php echo $picture['link'] ?>" class="swipebox">
                                <img src="<?php echo $picture['styles']['medium'] ?>" alt="...">
                            </a>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>

        </div>
    </div>
</div>
