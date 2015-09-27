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
    public $sourcePath = '@bower';

    public function init()
    {
        parent::init();
        $this->js[] = YII_DEBUG ?
            'handsontable/dist/handsontable.full.js' : 'handsontable/dist/handsontable.full.min.js';
        $this->css[] = YII_DEBUG ?
            'handsontable/dist/handsontable.full.css' : 'handsontable/dist/handsontable.full.min.css';
        $this->js[] = YII_DEBUG ? 'moment/moment.js' : 'moment/min/moment.min.js';
        $this->js[] = 'pikaday/pikaday.js';
        $this->css[] = 'pikaday/css/pikaday.css';
    }
}
