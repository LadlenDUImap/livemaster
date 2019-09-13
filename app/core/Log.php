<?php

namespace app\core;

use app\base\Component;

class Log extends Component
{
    public function set(string $message)
    {
        $errorStr = '';

        if (is_string($message)) {
            $errorStr .= $message;
        } elseif ($message instanceof \Exception) {
            $errorStr .= sprintf(
                "Произошла ошибка.\nCode: %s.\nMessage: %s.\nFile: %s.\nLine: %s.\nTrace: %s\n",
                $message->getCode(),
                $message->getMessage(),
                $message->getFile(),
                $message->getLine(),
                $message->getTraceAsString()
            );
        } else {
            $errorStr .= 'Неизвестная ошибка в функции логирования.';
        }

        $msg = date(DATE_RFC822) . ":\n" . $errorStr . "\n-------------------------------------------\n\n";
        error_log($msg);

        if (LM_DEBUG) {
            echo $errorStr;
        }

        return $errorStr;
    }
}
