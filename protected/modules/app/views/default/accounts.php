<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id => array('/'.$this->module->id),
	'Accounts'
);
?>
<h1>Connected accounts</h1>

<?php if(empty($accounts)) : ?>
<p>No accounts connected. Why not <?php $this->widget('bootstrap.widgets.TbButton', array('label'=>'add one now', 'url'=>array('addAccount'), 'type'=>'primary', 'size'=>'small')); ?>?</p>
<?php else : ?>
<?php endif; ?>