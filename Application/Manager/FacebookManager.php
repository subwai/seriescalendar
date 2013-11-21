<?php

class FacebookManager {
    private $Facebook;
    private $FacebookUser;

    protected static $instance = null;
    protected function __construct() {
        //Thou shalt not construct that which is unconstructable!
    }
    protected function __clone() {
        //Me not like clones! Me smash clones!
    }

    protected static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function FacebookInstance() {
        return FacebookManager::getInstance()->Facebook;
    }

    public function FacebookUser() {
        return FacebookManager::getInstance()->FacebookUser;
    }

    public function setFacebookInstance($asd) {
        $test = FacebookManager::getInstance();
        $test->Facebook = $asd;
    }

    public function setFacebookUser($user) {
        $test = FacebookManager::getInstance();
        $test->FacebookUser = $user;
    }
}

?>