<?php

namespace casinho\cropper;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;

/**
 * CropperWidget
 *
 * @url https://github.com/casinho/yii2-cropper-widgez
 * @author Carsten Tetzlaff <carsten-tetzlaff@web.de>
 */

class Cropper extends Widget {
    /** @var string Original image URL */
    public $image;
    /** @var float Aspect ratio for crop box. If not set(null) - it means free aspect ratio */
    public $aspectRatio;
    /** @var array HTML widget options */
    public $options = [];
    /** @var array Default HTML-options for image tag */
    public $defaultImageOptions = [
        'class' => 'cropper-image img-responsive',
        'alt' => 'crop-image',
    ];
    /** @var array HTML-options for image tag */
    public $imageOptions = [];
    /** @var array Default cropper options https://github.com/fengyuanchen/cropper/blob/master/README.md#options */
    public $defaultPluginOptions = [
        'strict' 			=> false,
        'autoCropArea' 		=> 0.75,
        'checkImageOrigin' 	=> false,
        'zoomable' 			=> true,
/*
  		'guides' 			=> false,
  		'highlight' 		=> false,
  		'dragCrop'			=> false,
  		'cropBoxMovable'	=> false,
  		'cropBoxResizable'	=> false,
*/  		        
    ];
    /** @var array Additional cropper options https://github.com/fengyuanchen/cropper/blob/master/README.md#options */
    public $pluginOptions = [];
    /** @var array Ajax options for send crop-reques */
    public $ajaxOptions = [
        'success' => 'js:function(data) { console.log(data); }',
    ];
    
   /** @var array Additional cropper options https://github.com/fengyuanchen/cropper/blob/master/README.md#options */
    public $cropButtons = [];

    /*
     *  Attention! I had to rename data-method to data-cropper-method cause of incompatible problems with pjax
     */
    
    public $defaultCropButtons = [
		[
			'move' => [
				['data-cropper-method' => 'move','data-option'=>'setDragMode','title'=>'Move','icon'=>'fa fa-arrows'],
			],
			'crop' => [
				['data-cropper-method' => 'crop','data-option'=>'setDragMode','title'=>'Crop','icon'=>'fa fa-crop'],
			],
		],
		[
    		'zoom' => [
				['data-cropper-method' => 'zoom','data-option'=>'0.1', 'title'=>'Zoom in','icon'=>'fa fa-search-plus'],
				['data-cropper-method' => 'zoom','data-option'=>'-0.1', 'title'=>'Zoom out','icon'=>'fa fa-search-minus'],
			],
		],
		[
			'reset' => [
				['data-cropper-method' => 'reset','title'=>'Reset','icon'=>'fa fa-refresh'],
			],
			'upload' => true,
		]
    ];    

    /**
     * @var string Path to view of cropbox field.
     * 
     * Example: '@backend/path/to/view'
     */
    public $pathToView = 'cropper';

    /**
     * @var string thumbHolder is a class for elements with images.
     * 
     */
    public $thumbHolder = 'thumbHolder';
    
    
    /**
     * @var boolean The size of the cropper inherits form the size of it´s parent element. In case I use X-Tabs I have to initialize the cropper again
     * 
     */
    public $initOnVisible = false;

    /**
     * @var string Id of hidden input filed
     */
    private $imageData = 'cropImageData';    
    
    /**
     * @inheritdoc
     */
    public function init() {

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        } else {
            $this->setId($this->options['id']);
        }

        $this->pluginOptions = ArrayHelper::merge($this->defaultPluginOptions, $this->pluginOptions);
        $this->imageOptions = ArrayHelper::merge($this->defaultImageOptions, $this->imageOptions);

        $this->cropButtons =  ArrayHelper::merge($this->defaultCropButtons, $this->cropButtons);
        
        // Set additional cropper js-options
        if (!empty($this->aspectRatio)) {
            $this->pluginOptions['aspectRatio'] = $this->aspectRatio;
        }
        
        parent::init();
        
        WidgetAsset::register($this->view);

        $this->options = array_merge([
            'accept' => 'image/*',
        ], $this->options);
        
        $optionsCropper = Json::encode($this->pluginOptions);

	    $js = "jQuery('#{$this->id}').myCropper({$optionsCropper});";
	   	// for cropping on show use: $js.= "jQuery('#{$this->id}').on('built.cropper cropend.cropper', function(e) {
	   	$js.= "jQuery('#{$this->id}').on('cropend.cropper', function(e) { 
	        var data = jQuery(this).cropper('getData');
	        //var original= jQuery(this).cropper('getImageData');
			//jQuery.extend(data,original) 
	        var value = JSON.stringify(data);
	        $('#{$this->imageData}').val(value); 
		});";
	    
	   	$jsCrop = $js;
   	
	    if($this->initOnVisible !== false) {

	    	
        	$js = "var parent = jQuery('#{$this->id}').closest('div[class^=\"tab-pane\"]');";
        	$js.= "var parentId = parent.attr('id');";

        	$js.= "jQuery('a[href=\'#'+parentId+'\']').on('shown.bs.tab', function (e) {
        		$jsCrop;	
	        });";
        }

    	if(!empty($this->image)) {
	   		$js.="jQuery('#{$this->id}').cropper('destroy');";
	   		$js.="jQuery('#{$this->id}').attr('src',jQuery(this).attr('{$this->image}'));";
	   		$js.= "jQuery('.{$this->thumbHolder} > img').on('click', function() {
	   				jQuery('#imageHolder').hide();
	   				jQuery('#imageUploader').show();	
	   		}) ;";
	   	}        
        
	   	/*
	   	 * Init cropper for existing images
	   	 * image must be child of an element with class=$this->thumbHulder 
	   	 */
	   	$js.= "jQuery('.{$this->thumbHolder} > img').on('click', function() {
	   		jQuery('#{$this->id}').cropper('destroy');
	   		jQuery('#{$this->id}').attr('src',jQuery(this).attr('data-src'));
	   		jQuery('.image-src').val(jQuery(this).attr('data-src'));
	   		$jsCrop;
    	}) ;"; 


        
        $this->view->registerJs($js, View::POS_READY);

    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render($this->pathToView);
    }    
    
    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }    
    
}
?>
