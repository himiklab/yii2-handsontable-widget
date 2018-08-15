Handsontable Widget for Yii2
============================
A minimalist Excel-like grid widget for Yii2 based on [Handsontable](https://github.com/handsontable/handsontable).

[![Packagist](https://img.shields.io/packagist/dt/himiklab/yii2-handsontable-widget.svg)]() [![Packagist](https://img.shields.io/packagist/v/himiklab/yii2-handsontable-widget.svg)]()  [![license](https://img.shields.io/badge/License-MIT-yellow.svg)]()

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist "himiklab/yii2-handsontable-widget" "*"
```

or add

```json
"himiklab/yii2-handsontable-widget" : "*"
```

to the require section of your application's `composer.json` file.

Usage
-----

```php
use himiklab\handsontable\HandsontableWidget;

<?= HandsontableWidget::widget([
    'settings' => [
        'data' => [
            ['A1', 'B1', 'C1'],
            ['A2', 'B2', 'C2'],
        ],
        'colHeaders' => true,
        'rowHeaders' => true,
    ]
]) ?>
```

or with ActiveRecord

in view:
```php
use himiklab\handsontable\HandsontableWidget;

<?= HandsontableWidget::widget([
    'requestUrl' => 'hts',
    'isRemoteChange' => true,
]); ?>
```

in controller:
```php
use app\models\Page;
use himiklab\handsontable\actions\HandsontableActiveAction;

public function actions()
{
    return [
        'hts' => [
            'class' => HandsontableActiveAction::className(),
            'model' => Page::className(),
            'isChangeable' => true,
        ],
    ];
}
```
