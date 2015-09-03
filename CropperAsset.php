<?php

namespace demi\cropper;

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
    public $css = [
        YII_DEBUG ? 'cropper/dist/cropper.css' : 'cropper/dist/cropper.min.css',
    ];
    public $js = [
        YII_DEBUG ? 'cropper/dist/cropper.js' : 'cropper/dist/cropper.min.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
