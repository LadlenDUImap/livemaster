<?php

namespace app\core;

use app\base\Component;

/**
 * Csrf служит для защиты от CSRF атак.
 *
 * @package core
 */
class Csrf extends Component
{
    public $token_name;

    public $token_salt;


    /** @var array методы при которых нужна проверка на CSRF */
    protected $requestMethodsToCheck = ['POST', 'PUT', 'DELETE'];

    /** @var array методы при которых НЕ нужна проверка на CSRF */
    protected $requestMethodsAllowed = ['GET'];

    public function getCsrfTokenName()
    {
        return $this->token_name;
        //return Lm::$app->csrf->token_name;
    }

    /**
     * Создает и сохраняет CSRF токен если надо.
     *
     * @return null|string
     */
    public function getCsrfToken()
    {
        if (!$csrfToken = $this->loadCsrfToken())
        {
            //$salt = Lm::$app->csrf->token_salt;       //'uIlmkI873d';
            $csrfToken = $this->token_salt . ':' .  md5(openssl_random_pseudo_bytes(15));
            $this->storeCsrfToken($csrfToken);
        }

        return $csrfToken;
    }

    protected function storeCsrfToken($csrfToken)
    {
        Lm::$app->web->startSession();
        $_SESSION['csrf']['token'] = $csrfToken;
    }

    protected function loadCsrfToken()
    {
        Lm::$app->web->startSession();
        return (!empty($_SESSION['csrf']['token'])) ? $_SESSION['csrf']['token'] : null;
    }

    /**
     * Проверка на правильность CSRF токена.
     *
     * @return bool
     */
    public function validateCsrfToken()
    {
        $ret = false;

        $tokenName = $this->getCsrfTokenName();

        if (!empty($_SERVER['REQUEST_METHOD']))
        {
            $method = strtoupper($_SERVER['REQUEST_METHOD']);
            if (in_array($method, $this->requestMethodsToCheck))
            {
                $csrfToken = $this->loadCsrfToken();

                if ($csrfToken) {
                    $method = '_' . $method;
                    global ${$method};
                    $ret = ((isset(${$method}[$tokenName]) && ${$method}[$tokenName] == $csrfToken)
                        || (isset($_COOKIE[$tokenName]) && $_COOKIE[$tokenName] == $csrfToken));
                } else {
                    //throw new \Exception('Проблемы с сессией');
                }
            }
            elseif (in_array($method, $this->requestMethodsAllowed))
            {
                $ret = true;
            } else {
                Lm::$app->log->set('Неизвестный метод: ' . $method, 'error');
            }
        }

        return $ret;
    }
}