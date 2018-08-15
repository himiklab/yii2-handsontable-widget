<?php
/**
 * @link https://github.com/himiklab/yii2-handsontable-widget
 * @copyright Copyright (c) 2014-2018 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\handsontable;

use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Handsontable grid widget for Yii2.
 *
 * For example:
 *
 * ```php
 * echo HandsontableWidget::widget([
 *  'settings' => [
 *      'data' => [
 *          ['A1', 'B1', 'C1'],
 *          ['A2', 'B2', 'C2'],
 *      ],
 *  'colHeaders' => true,
 *  'rowHeaders' => true,
 *  ]
 * ]);
 * ```
 *
 * @author HimikLab
 * @package himiklab\handsontable
 */
class HandsontableWidget extends Widget
{
    /**
     * @var array $settings
     * @see https://github.com/handsontable/handsontable
     */
    public $settings = [];

    /** @var string */
    public $varPrefix = 'hst_';

    /** @var string|null */
    public $requestUrl;

    /** @var bool */
    public $isRemoteChange = false;

    /** @var string */
    protected $jsVarName;

    public function run()
    {
        $view = $this->getView();

        $settings = Json::encode(
            $this->settings,
            (YII_DEBUG ? JSON_PRETTY_PRINT : 0) | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK
        );

        HandsontableAsset::register($view);
        $this->jsVarName = $this->varPrefix . $this->id;
        $view->registerJs(
            "var {$this->jsVarName} = new Handsontable(document.getElementById('handsontable-{$this->id}'), {$settings})",
            $view::POS_READY
        );

        if ($this->requestUrl) {
            $this->prepareRemoteSettings();
        }

        echo "<div id='handsontable-{$this->id}'></div>" . PHP_EOL;
    }

    protected function prepareRemoteSettings()
    {
        $view = $this->getView();
        $requestUrl = Url::to([$this->requestUrl, 'action' => 'request']);
        $changeUrl = Url::to([$this->requestUrl, 'action' => 'change']);

        $view->registerJs(
            <<<JS
"use strict";
var pkData = [];
var attributesData = [];
jQuery.ajax({
    url: "{$requestUrl}",
    method: "POST",
    dataType: "JSON",
    success: function(result) {
        pkData = result.pk;
        attributesData = result.attributes;
        {$this->jsVarName}.loadData(result.data);
    }
});
JS
        );

        if ($this->isRemoteChange) {
            $view->registerJs(
                <<<JS
"use strict";
{$this->jsVarName}.updateSettings({
    afterChange: function (change, source) {
        if (source === "loadData") {
            return;
        }

        var result = {};
        change.forEach(function(item) {
            var pkKey = pkData[item[0]];
            var attributeKey = attributesData[item[1]];
            if (result[pkKey] === undefined) {
                result[pkKey] = {};
            }

            result[pkKey][attributeKey] = item[3];
        });

        jQuery.ajax({
            url: "{$changeUrl}",
            method: "POST",
            data: {data: JSON.stringify(result)}
        });
    }
});
JS
            );
        }
    }
}
