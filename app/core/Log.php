<?php

namespace app\core;

use app\base\Component;

class Log extends Component
{
    public $log_file_prefix;

    public function set(string $message, $type = 'info')
    {
        $str = '';

        if (is_string($message)) {
            $str .= $message;
        } elseif ($message instanceof \Exception) {
            $str .= sprintf(
                "Произошла ошибка.\nCode: %s.\nMessage: %s.\nFile: %s.\nLine: %s.\nTrace: %s\n",
                $message->getCode(),
                $message->getMessage(),
                $message->getFile(),
                $message->getLine(),
                $message->getTraceAsString()
            );
        } else {
            $str .= 'Неизвестная ошибка в функции логирования.';
        }

        $msg = date(DATE_RFC822) . ":\n" . $str . "\n-------------------------------------------\n\n";

        if ($type == 'error') {
            error_log($msg);
            if (LM_DEBUG) {
                echo $str;
            }
        } else {
            if ($this->log_file_prefix) {
                file_put_contents($this->log_file_prefix . "_$type.log", $msg, FILE_APPEND);
            }
        }

        return $str;
    }
}
