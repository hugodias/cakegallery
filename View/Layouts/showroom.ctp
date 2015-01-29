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
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>

    <?php echo $this->Html->css(
        array(
            'Gallery.themes/' . Configure::read('GalleryOptions.App.theme') . '.min'
        )
    ); ?>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery.swipebox/1.3.0.2/css/swipebox.min.css"/>

    <?php echo $this->Html->script('Gallery.lib/modernizr') ?>
    <?php echo $this->fetch('css'); ?>
</head>
<body class="<?php echo $this->params->params['controller'] . '_' . $this->params->params['action'] ?>"
      data-base-url="<?php echo $this->params->webroot ?>"
      data-plugin-base-url="<?php echo $this->Html->url(
          array('plugin' => 'gallery', 'controller' => 'gallery', 'action' => 'index')
      ) ?>">
<!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser
    today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better
    experience this site.</p>
<![endif]-->

<div class="container">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo $this->params->webroot ?>js/lib/jquery.min.js"><\/script>')</script>

<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.swipebox/1.3.0.2/js/jquery.swipebox.min.js"></script>
<?php echo $this->fetch('js'); ?>

</body>
</html>
