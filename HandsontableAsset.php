<?php
/**
 * @link https://github.com/himiklab/yii2-handsontable-widget
 * @copyright Copyright (c) 2014-2018 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\handsontable;

use yii\web\AssetBundle;

class HandsontableAsset extends AssetBundle
{
    public $sourcePath = '@bower/handsontable/dist';

    public function init()
    {
        parent::init();

        $this->js[] = 'moment/moment.js';
        $this->js[] = 'numbro/numbro.js';
        $this->js[] = 'pikaday/pikaday.js';
        $this->js[] = YII_DEBUG ? 'handsontable.full.js' : 'handsontable.full.min.js';
        $this->js[] = YII_DEBUG ? 'languages/all.js' : 'languages/all.min.js';

        $this->css[] = 'pikaday/pikaday.css';
        $this->css[] = YII_DEBUG ? 'handsontable.full.css' : 'handsontable.full.min.css';
    }
}
