<?php
/**
 * @link https://github.com/himiklab/yii2-handsontable-widget
 * @copyright Copyright (c) 2014-2018 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\handsontable;

use yii\web\AssetBundle;

class NumbroAsset extends AssetBundle
{
    public $sourcePath = '@bower/numbro/dist';

    public function init()
    {
        parent::init();
        $this->js[] = YII_DEBUG ? 'numbro.js' : 'numbro.min.js';
    }
}