<?php

namespace App\Controller;

/**
 * Class SessionController
 * @package App\Controller
 */
abstract class SessionController
{
    /**
     * @var array|mixed
     */
    protected $session = null;

    /**
     * SessionController constructor.
     */
    public function __construct()
    {
        $this->session = filter_var_array($_SESSION);
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param bool $admin
     */
    public function createSession(int $id, string $name, string $email, bool $admin)
    {
        $_SESSION['user'] = [
            'id'    => $id,
            'name'  => $name,
            'email' => $email,
            'admin' => $admin
        ];
    }

    /**
     * @return void
     */
    public function destroySession()
    {
        $_SESSION['user'] = [];
        session_destroy();
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        if (array_key_exists('user', $this->session)) {

            if (!empty($this->session['user'])) {

                return true;
            }
        }
        return false;
    }

       /**
     * @param $var
     * @return mixed
     */
    public function getUserVar($var)
    {
        if ($this->isLogged() === false) {
            $this->session['user'][$var] = null;
        }

        return $this->session['user'][$var];
    }
}