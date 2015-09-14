<?php

namespace casinho\cropper;

use yii\web\AssetBundle;

/**
 * CropperAsset
 *
 * @url https://github.com/casinho/yii2-cropper-widgez
 * @author Carsten Tetzlaff <carsten-tetzlaff@web.de>
 */
class CropperAsset extends AssetBundle
{
    public $sourcePath = '@bower';
				
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    
    public function init() {
    	
    	$this->css = [
			'cropper/dist/cropper'.(!YII_DEBUG ? '.min' : '') . '.css'
		];
    	$this->js = [
			'cropper/dist/cropper'.(!YII_DEBUG ? '.min' : '') . '.js'
		];
    	
    }
    
}
?>
