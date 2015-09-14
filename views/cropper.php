<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use casinho\cropper\Cropper;

?> 
	<div class="row" id="imageHolder" style="<?php if($this->context->image!='') { echo 'display:block;'; } else { echo 'display:none'; }?>">
		<div class="col-md-9">
			<div>
<?php

$route = implode('/',[Yii::$app->controller->id,Yii::$app->controller->action->id]);
$url = Url::to([$route,'id'=>Yii::$app->request->get('id'),'file_id'=>0]);

$span = Html::tag('span','',['class'=>'fa fa-upload mr5']);
$span.= Html::tag('span','ein neues Foto hochladen');
$btn = Html::a($span,$url,['class'=>'btn btn-primary btn-xs ml5']);


echo \insolita\wgadminlte\Callout::widget([
	'type'=>\insolita\wgadminlte\Alert::TYPE_INFO,
    'head'=>Yii::t('backend','Hinweis'),
    'text'=>Yii::t('backend','Um das Bild zu ändern, einfach unter Originale ein Bild auswählen oder '.$btn.'.'),
	'options' => [
		'style'=>'margin-bottom:20px',
	]
]);
?>				
			<img src="<?=$this->context->image;?>">
			</div>
		</div>
	</div>


	<div class="row" id="imageUploader" style="<?php if($this->context->image!='') { echo 'display:none;'; }?>">
    	<div class="col-md-9">
			<div class="img-container">
				<img src="<?= $this->context->image; ?>" id="<?= $this->context->id; ?>" alt="Picture">
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

#dd($this->context->cropButtons);
echo Html::hiddenInput('image_src','',['class'=>'image-src']);		
echo Html::hiddenInput('image_data','',['class'=>'image-data','id'=>'cropImageData']);
//echo Html::input('text','image_data','',['class'=>'image-data','id'=>'cropImageData','style'=>'width:100%']);



foreach($this->context->cropButtons as $btnGroup) {
	echo Html::beginTag('div',['class'=>'btn-group']);
	foreach($btnGroup as $name => $data) {
		if($name == 'upload') {
			echo Html::beginTag('label',['title'=>'Upload Image','for'=>'inputImage','class'=>'btn btn-primary btn-upload']);
			echo Html::fileInput('image','',['accept'=>'image/*','id'=>'inputImage','class'=>'sr-only']);
			echo Html::tag('span','',['class'=>'fa fa-upload mr5']);
			echo Html::tag('span','Bild auswählen');
			echo Html::endTag('label');
		} else {
						
			foreach($data as $value) {
				echo Html::beginTag('button',['data-pjax'=>0,'data-option'=>isset($value['data-option']) ? $value['data-option'] : '','data-cropper-method'=>isset($value['data-cropper-method']) ? $value['data-cropper-method'] : '','class'=>'btn btn-primary','type'=>'button']);
				echo Html::tag('span','',['class'=>isset($value['icon']) ? $value['icon'] : '']);
				echo Html::endTag('button');
			}
		}
	}
	echo Html::endTag('div');
}
	if($this->context->image!='') {
		echo Html::beginTag('button',['data-pjax'=>0,'onclick'=>'$("#imageUploader").hide();$("#imageHolder").show();','data-option'=>isset($value['data-option']) ? $value['data-option'] : '','data-cropper-method'=>'destroy','class'=>'btn btn-warning','type'=>'button']);
		echo Html::tag('span',' ',['class'=>'fa fa-remove mr5']).Yii::t('backend','Abbrechen');
		echo Html::endTag('button');
	}


?>

 
			</div>
		</div>
	</div>
