<?php

namespace app\core;

use app\helpers\Helper;
use app\base\Singleton;

class Lm extends Singleton
{
    public function __construct($config)
    {
        Di::set($this, $config['components']);
    }

    public function run()
    {
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
