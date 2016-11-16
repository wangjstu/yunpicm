<?php
namespace common\components;

use Yii;
use yii\base\Component;
/**
 * Created by PhpStorm.
 * User: wangjstu
 * Date: 2016/10/15
 * Time: 15:07
 */

class ServiceSupport extends Component
{
    public function powered()
    {
        return Yii::t('app', 'Powered by {yumpicm}', [
            'yumpicm' => '<a href="#" rel="external">' . \Yii::t('app',
                'yumpicm') . '</a>'
        ]);
    }
}