<?php

namespace app\components;

use app\src\Application\Context\RequestContext as Context;
use yii\base\Component;

class RequestContext extends Component
{
    private Context $context;

    public function getContext(): Context
    {
        return $this->context;
    }

    public function setContext(Context $value)
    {
        $this->context = $value;
    }
}
