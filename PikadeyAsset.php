<?php
/**
 * @link https://github.com/himiklab/yii2-handsontable-widget
 * @copyright Copyright (c) 2014-2018 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\handsontable;

use yii\web\AssetBundle;

class PikadeyAsset extends AssetBundle
{
    public $sourcePath = '@bower/pikaday';

    public $js = [
        'pikaday.js'
    ];

    public $css = [
        'css/pikaday.css'
    ];
}
