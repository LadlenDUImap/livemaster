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
    /** @var array методы при которых нужна проверка на CSRF */
    protected $requestMethodsToCheck = ['POST', 'PUT', 'DELETE'];

    /** @var array методы при которых НЕ нужна проверка на CSRF */
    protected $requestMethodsAllowed = ['GET'];

    public function getCsrfTokenName()
    {
        return Lm::inst()->csrf->token_name;
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
            $salt = Lm::inst()->csrf->token_salt;       //'uIlmkI873d';
            $csrfToken = $salt . ':' .  md5(openssl_random_pseudo_bytes(15));
            $this->storeCsrfToken($csrfToken);
        }

        return $csrfToken;
    }

    protected function storeCsrfToken($csrfToken)
    {
        Web::startSession();
        $_SESSION['csrf']['token'] = $csrfToken;
    }

    protected function loadCsrfToken()
    {
        Web::startSession();
        return (isset($_SESSION['csrf']['token'])) ? $_SESSION['csrf']['token'] : null;
    }

    /**
     * Проверка на правильность CSRF токена.
     *
     * @return bool
     */
    /*public function validateCsrfToken()
    {
        $ret = false;

        $tokenName = $this->getCsrfTokenName();

        if (!empty($_SERVER['REQUEST_METHOD']))
        {
            $method = strtoupper($_SERVER['REQUEST_METHOD']);
            if (in_array($method, $this->requestMethodsToCheck))
            {
                if (!$this->loadCsrfToken())
                {
                    // Возможно закончилась сессия на сервере.
                    //TODO: что с этим делать???
                    //\core\Web::redirect('?session_expired=1');
                    Web::redirect('?action=sessionExpired');
                }

                $method = '_' . $method;
                global ${$method};
                $ret = ((isset(${$method}[$tokenName]) && ${$method}[$tokenName] == $this->loadCsrfToken())
                    || (isset($_COOKIE[$tokenName]) && $_COOKIE[$tokenName] == $this->loadCsrfToken()));
            }
            elseif (in_array($method, $this->requestMethodsAllowed))
            {
                $ret = true;
            }
        }

        return $ret;
    }*/
}