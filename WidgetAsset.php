<?php

namespace casinho\cropper;

use yii\web\AssetBundle;

/**
 * Core assets of widget.
 * 
 * @author Carsten Tetzlaff <https://github.com/casinho>
 */
class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@casinho/cropper/assets';
    public $css = [
        'css/base.css',
    ];
    public $js = [
        'js/base.js',
    ];    
    public $depends = [
        'casinho\cropper\CropperAsset',
    ];
}
?>
