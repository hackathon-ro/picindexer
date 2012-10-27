<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id => array('/'.$this->module->id),
	'Add account'
);

?>
<h1>Add new account</h1>

<ul>
<?php foreach($accountTypes as $at) : ?>
	<li><?php $this->widget('bootstrap.widgets.TbButton', array('label'=>$at['label'], 'url'=>$at['url'])); ?></li>
<?php endforeach; ?>
</ul>