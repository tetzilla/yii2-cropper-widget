<?php

namespace casinho\cropper;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
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
        'strict' => true,
        'autoCropArea' => 1,
        'checkImageOrigin' => false,
        'zoomable' => false,
    ];
    /** @var array Additional cropper options https://github.com/fengyuanchen/cropper/blob/master/README.md#options */
    public $pluginOptions = [];
    /** @var array Ajax options for send crop-reques */
    public $ajaxOptions = [
        'success' => 'js:function(data) { console.log(data); }',
    ];
    
   /** @var array Additional cropper options https://github.com/fengyuanchen/cropper/blob/master/README.md#options */
    public $cropButtons = [];

    public $defaultCropButtons = [
	[
		'move' => [
			['data-method' => 'move','data-option'=>'setDragMode','title'=>'Move'],
		],
		'crop' => [
			['data-method' => 'crop','data-option'=>'setDragMode','title'=>'Crop'],
		],
	],
	[
    		'zoom' => [
			['data-method' => 'zoom','data-option'=>'0.1', 'title'=>'Zoom in'],
			['data-method' => 'zoom','data-option'=>'-0.1', 'title'=>'Zoom out'],
		],
	],
	[
		'reset' => [
			['data-method' => 'reset','title'=>'Reset'],
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

        // Set additional cropper js-options
        if (!empty($this->aspectRatio)) {
            $this->pluginOptions['aspectRatio'] = $this->aspectRatio;
        }
        
        parent::init();
        
        WidgetAset::register($this->view);

        $this->options = array_merge([
            'accept' => 'image/*',
        ], $this->options);
        
        $optionsCropper = Json::encode($this->pluginOptions);

        $js = "$('#{$this->id}').cropper({$optionsCropper});";
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
