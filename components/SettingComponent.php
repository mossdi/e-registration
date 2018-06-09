<?php

namespace app\components;

use Exception;
use yii\base\Component;
use app\entities\Setting;

/**
 * Class SettingComponent
 * @package common\components
 */
class SettingComponent extends Component {

    protected $data = array();

    public function init() {
        parent::init();

        $items = Setting::find()->all();

        foreach ($items as $item) {
            if ($item->param) {
                if ($item->type === 'integer') {
                    $this->data[$item->param] = $item->value === '' ? (int)$item->default : (int)$item->value;
                } else {
                    $this->data[$item->param] = $item->value === '' ? $item->default : $item->value;
                }
            }
        }
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function get($key) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            throw new Exception('Undefined parameter ' . $key);
        }
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function set($key, $value) {
        $model = Setting::model()->findByAttributes(array('param' => $key));

        if (!$model) {
            throw new Exception('Undefined parameter ' . $key);
        }

        $this->data[$key] = $value;

        $model->value = $value;

        $model->save();
    }
}
