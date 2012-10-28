<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->name => array('/'.$this->module->id),
	'Accounts'
);
?>
<h1>Connected accounts</h1>

<?php $this->widget('bootstrap.widgets.TbAlert', array('alerts'=>array('success','warning','error')));?>

<?php if(empty($accounts)) : ?>
<p>No accounts connected. Why not <?php $this->widget('bootstrap.widgets.TbButton', array('label'=>'add one now', 'url'=>array('add', 'type'=>'facebook'), 'type'=>'primary', 'size'=>'small')); ?>?</p>
<?php endif; ?>
<?php $gridDataProvider = new CArrayDataProvider($accounts); ?>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped condensed',
	'dataProvider'=>$gridDataProvider,
	'template'=>"{items}",
	'columns'=>array(
		array('name'=>'typeIcon', 'header'=>'Service'),
		array('name'=>'name', 'header'=>'Account name'),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'htmlOptions'=>array('style'=>'width: 15px'),
			'template'=>'{delete}',
		),
	),
)); ?>

<?php if(!empty($accounts)) : ?>
<?php $this->widget('bootstrap.widgets.TbButton', array('label'=>'Add another one', 'url'=>array('add', 'type'=>'facebook'), 'type'=>'success', 'icon'=>'plus white', 'size'=>'small')); ?>
<?php endif; ?>