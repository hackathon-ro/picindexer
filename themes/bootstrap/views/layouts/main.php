<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <style>
      body {
        padding-top: 60px;
      	padding-botton: 40px;
      }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <?php $this->widget('bootstrap.widgets.TbNavbar', array(
      //'type'=>'inverse', // null or 'inverse'
      'brand'=>Yii::app()->name,
      'brandUrl'=>Yii::app()->user->isGuest?array('site/index'):array('/app'),
      'collapse'=>true, // requires bootstrap-responsive.css
      'items'=>array(
        array(
          'class'=>'bootstrap.widgets.TbMenu',
          'htmlOptions'=>array(
            'class'=>'pull-right',
          ),
          'items'=>array(
            array('label'=>'Connected accounts', 'url'=>array('/app/account/index'), 'visible'=>!Yii::app()->user->isGuest),
            array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
            array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
          ),
        ),
      ),
    )); ?>

    <div class="container">
      <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
        'links'=>$this->breadcrumbs,
      )); ?>

      <?php echo $content; ?>

      <hr>

      <footer>
        <p>&copy; 2012</p>
      </footer>

    </div> <!-- /container -->

  </body>
</html>
