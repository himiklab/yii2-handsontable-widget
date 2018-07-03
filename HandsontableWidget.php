<?php
/**
 * @link https://github.com/himiklab/yii2-handsontable-widget
 * @copyright Copyright (c) 2014-2018 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\handsontable;

use yii\base\Widget;
use yii\helpers\Json;

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
     * @var string $settings
     * @see https://github.com/handsontable/handsontable
     */
    public $settings = '';

    public function init()
    {
        parent::init();
        $view = $this->getView();
        $settings = Json::encode(
            $this->settings,
            (YII_DEBUG ? JSON_PRETTY_PRINT : 0) | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK
        );

        HandsontableAsset::register($view);
        $view->registerJs(
            "var hst_{$this->id} = new Handsontable(document.getElementById('handsontable-{$this->id}'), {$settings})",
            $view::POS_READY
        );
    }

    public function run()
    {
        echo "<div id='handsontable-{$this->id}'></div>" . PHP_EOL;
    }
}
