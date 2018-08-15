<?php
/**
 * @link https://github.com/himiklab/yii2-handsontable-widget
 * @copyright Copyright (c) 2014-2018 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\handsontable\actions;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Action for Handsontable widget based on ActiveDataProvider.
 *
 * For example:
 *
 * ```php
 * public function behaviors()
 * {
 *      return [
 *           'hts' => [
 *              'class' => HandsontableActiveAction::className(),
 *              'model' => Page::className(),
 *          ],
 *      ];
 * }
 * ```
 *
 * @author HimikLab
 * @package himiklab\handsontable\actions
 */
class HandsontableActiveAction extends Action
{
    const COMPOSITE_KEY_DELIMITER = '%';

    /** @var string|ActiveRecord $model */
    public $model;

    /**
     * @var array|callable $columns the columns being selected.
     * This is used to construct the SELECT clause in a SQL statement. If not set, it means selecting all columns.
     */
    public $columns = [];

    /** @var callable */
    public $scope;

    /** @var bool */
    public $isChangeable = false;

    public function run()
    {
        if (!\is_subclass_of($this->model, ActiveRecord::className())) {
            throw new InvalidConfigException('The `model` param must be object or class extends \yii\db\ActiveRecord.');
        }
        if (\is_string($this->model)) {
            $this->model = new $this->model;
        }
        if (!$getActionParam = Yii::$app->request->get('action')) {
            throw new BadRequestHttpException('GET param `action` isn\'t set.');
        }

        if (\is_callable($this->columns)) {
            $this->columns = \call_user_func($this->columns);
        }

        $model = $this->model;
        if (empty($this->columns)) {
            $this->columns = $model->attributes();
        }

        switch ($getActionParam) {
            case 'request':
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $this->requestAction();
            case 'change':
                if ($this->isChangeable) {
                    $this->changeAction(Json::decode(Yii::$app->request->post('data')));
                }
                break;
            default:
                throw new BadRequestHttpException('Unsupported GET `action` param.');
        }
    }

    /**
     * @return array JSON answer
     */
    protected function requestAction()
    {
        $model = $this->model;
        $query = $model::find();
        if (\is_callable($this->scope)) {
            \call_user_func($this->scope, $query);
        }

        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $response = [];
        $column = 0;
        foreach ($this->columns as $modelAttribute) {
            $response['data'][0][] = $model->getAttributeLabel($modelAttribute);
            $response['attributes'][$column] = $modelAttribute;
            ++$column;
        }

        $row = 1;
        foreach ($dataProvider->getModels() as $record) {
            if (\is_array($record->primaryKey)) {
                $response['pk'][$row] = \implode(self::COMPOSITE_KEY_DELIMITER, $record->primaryKey);
            } else {
                $response['pk'][$row] = $record->primaryKey;
            }

            $column = 0;
            /** @var \yii\db\ActiveRecord $record */
            foreach ($this->columns as $modelAttribute) {
                $response['data'][$row][$column] = $record->{$modelAttribute};
                ++$column;
            }
            ++$row;
        }

        return $response;
    }

    /**
     * @param array $requestData
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    protected function changeAction($requestData)
    {
        $model = $this->model;
        $modelPK = $model::primaryKey();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($requestData as $pk => $modelData) {
                if (\count($modelPK) > 1) {
                    $pkParts = \explode(self::COMPOSITE_KEY_DELIMITER, $pk);
                    $recordCondition = \array_combine($modelPK, $pkParts);
                } else {
                    $recordCondition = $pk;
                }

                /** @var \yii\db\ActiveRecord $record */
                if (($record = $model::findOne($recordCondition)) === null) {
                    continue;
                }

                foreach ($modelData as $attribute => $attributeValue) {
                    $record->{$attribute} = $attributeValue;
                }

                if (!$record->save()) {
                    throw new Exception(print_r($record->getErrors(), true));
                }
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->commit();
    }
}
