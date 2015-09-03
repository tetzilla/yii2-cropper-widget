<?php

use yii\helpers\Html;
use casinho\cropper\Cropper;

?> 
<div class="container">
	<div class="row">
    	<div class="col-md-9">
			<div id="<?= $this->context->id; ?>" class="img-container">
				<img src="<?= $this->context->id; ?>" alt="Picture">
			</div>
		</div>
      	<div class="col-md-3">
        <!-- <h3 class="page-header">Preview:</h3> -->
        <div class="docs-preview clearfix">
        	<div class="img-preview preview-lg"></div>
          	<div class="img-preview preview-md"></div>
          	<div class="img-preview preview-sm"></div>
          	<div class="img-preview preview-xs"></div>
        </div>
	</div>
	<div class="row">
    	<div class="col-md-9 docs-buttons">

<?php 
foreach($this->context->cropButtons as $key => $btnName) {
	echo Html::beginTag('div',['class'=>'btn-group']);
	foreach($btnName as $k => $v) {
		if($k == 'upload') {
			echo Html::beginTag('label',['title'=>'Upload Image','for'=>'inputImage','class'=>'btn-btn-primary btn-upload']);
			echo Html::input($type,'file','',['accept'=>'image/*','id'=>'inputImage','class'=>'sr-only']);
			echo Html::tag('span',['class'=>'fa fa-upload']);
			echo Html::endTag('label');
		} else {
			$title = $v['title'];
			unset($v['title']);	
			echo Html::button($v['title'],$v);
		}
	}
	echo Html::endTag('div');
}
?>
	
		</div>
	</div>
</div>
