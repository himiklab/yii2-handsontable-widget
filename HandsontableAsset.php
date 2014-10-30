<?php
/**
 * @link https://github.com/himiklab/yii2-handsontable-widget
 * @copyright Copyright (c) 2014 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\handsontable;

use yii\web\AssetBundle;

class HandsontableAsset extends AssetBundle
{
    public $sourcePath = '@bower/handsontable/dist';

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
        $this->js[] = YII_DEBUG ? 'jquery.handsontable.full.js' : 'jquery.handsontable.full.min.js';
        $this->css[] = YII_DEBUG ? 'jquery.handsontable.full.css' : 'jquery.handsontable.full.min.css';
    }
}
