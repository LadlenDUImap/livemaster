<?php

namespace app\core;

use app\helpers\Helper;
use app\base\Singleton;

class Lm extends Singleton
{
    //public static $app;

    public function run($config)
    {
        Di::set($this, $config['components']);

        //self::$app = $this;

        try {
            /*if (!Csrf::inst()->validateCsrfToken()) {
                //TODO: похоже неправильное поведение (см. validateCsrfToken())
                Web::redirect('?wrong_csrf_token=1');
            }*/

            (new Router)->run();
        } catch (\Exception $e) {
            if (LM_DEBUG) {
                $displayMsg = Helper::log($e);
            } else {
                $msg = sprintf(_('Server error, code %s. Please contact administrator.'), $e->getCode());
                $displayMsg = Helper::log($msg);
            }
            echo $displayMsg;
        }
    }
}
