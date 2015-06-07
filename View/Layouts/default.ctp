<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $title_for_layout; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <?php echo $this->Html->css(array(
        '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css',
        '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/jquery.swipebox/1.3.0.2/css/swipebox.min.css',
        'Gallery.bootstrap-editable',
        'Gallery.sweetalert')) ?>


    <?php if (Configure::read('GalleryOptions.App.interfaced')) { ?>
        <?php echo $this->Html->css(
            array(
                'Gallery.themes/' . Configure::read('GalleryOptions.App.theme') . '.min'
            )
        ); ?>
    <?php } ?>

    <?php echo $this->Html->script('Gallery.lib/modernizr') ?>
    <?php echo $this->fetch('css'); ?>
</head>
<body class="<?php echo $this->params->params['controller'] . '_' . $this->params->params['action'] ?>"
      data-base-url="<?php echo $this->params->webroot ?>"
      data-plugin-base-url="<?php echo $this->Html->url(
          array('plugin' => 'gallery', 'controller' => 'gallery', 'action' => 'index')
      ) ?>">

<div id="canvasup"></div>

<?php echo $this->Html->script(array(
    '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js',
    '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/jquery.swipebox/1.3.0.2/js/jquery.swipebox.min.js',
    'Gallery.lib/bootstrap-editable.min',
    'Gallery.lib/mustache.min',
    'Gallery.lib/sweetalert.min'
)) ?>

<?php echo $this->fetch('js'); ?>

<?php if (Configure::read('GalleryOptions.App.interfaced')) { ?>
    <div class="container">
        <div class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand"
                       href="<?php echo $this->Html->url(
                           array('controller' => 'gallery', 'action' => 'index', 'plugin' => 'gallery')
                       ) ?>">CakeGallery</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li>
                            <?php echo $this->Html->link(
                                'Albums',
                                array('controller' => 'gallery', 'action' => 'index', 'plugin' => 'gallery')
                            ) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
    </div>
<?php } else { ?>
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
<?php } ?>
</body>
</html>
