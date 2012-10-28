<?php Yii::trace(CVarDumper::dumpAsString($data)); ?>
<li class="span3">
	<?php $htmlOptions = array(
		'class'=>'thumbnail',
		'target'=>'_blank',
	);
	if($data->account) {
		$htmlOptions['rel'] = 'tooltip';
		$htmlOptions['data-title'] = 'From '.$data->account->name.' on '.$data->account->type;
	}
	?>
	<?php echo CHtml::link(CHtml::image($data->url), $data->remote_url, $htmlOptions); ?>
</li>