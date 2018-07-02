<?php

namespace app\helpers;

/**
 * Helper содержит общие вспомогательные функции.
 *
 * @package helpers
 */
class Helper
{
    /**
     * Запись логов.
     *
     * @param string|\Exception $message Сообщение или объект исключения.
     * @return string Текст, подготовленный для сохранения в лог.
     */
    public static function log($message)
    {
        $errorStr = '';

        if (is_string($message)) {
            $errorStr .= $message;
        } elseif ($message instanceof \Exception) {
            $errorStr .= sprintf(
                _("Error occured.\nCode: %s.\nMessage: %s.\nFile: %s.\nLine: %s.\nTrace: %s\n"),
                $message->getCode(),
                $message->getMessage(),
                $message->getFile(),
                $message->getLine(),
                $message->getTraceAsString()
            );
        } else {
            $errorStr .= _('An unrecognized error occured in the error log function.');
        }

        $msg = date(DATE_RFC822) . ":\n" . $errorStr . "\n-------------------------------------------\n\n";
        error_log($msg);

        return $errorStr;
    }
}