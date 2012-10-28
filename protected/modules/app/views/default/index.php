<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->name,
);
?>

<div class="hero-unit">
	<h1>Search your photos</h1>
	<?php if($model->s && empty($results)) : ?>
	<p>Unfortunately, searching for <i><?php echo CHtml::encode($model->s); ?></i> yielded no results. Try something else.</p>
	<?php else : ?>
	<p>Type in some keywords that resemble picture contents</p>
	<?php endif; ?>
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'type' => 'search',
	)); ?>
	<?php echo $form->textFieldRow($model, 's', array('class'=>'input-large', 'prepend'=>'<i class="icon-search"></i>')); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Go')); ?>
	<?php $this->endWidget(); ?>
</div>
<?php if(!empty($results)) : ?>
<h2>Photos matching <i><?php echo CHtml::encode($model->s); ?></i></h2>
<?php $this->widget('bootstrap.widgets.TbThumbnails', array(
	'dataProvider'=>new CArrayDataProvider($results),
	'template'=>"{items}\n{pager}",
	'itemView'=>'_photo',
)); ?>
<?php endif; ?>