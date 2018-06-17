<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * highcharts
 */
class HighchartsAsset extends AssetBundle {

	public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'highcharts/code/css/highcharts.css',
    ];
    public $js = [
        'highcharts/code/highcharts.js',
    ];
}
