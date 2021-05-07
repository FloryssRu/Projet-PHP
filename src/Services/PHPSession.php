<?php

namespace App\Services;

class PHPSession
{
    
    /**
     * Verify that a session is already started, else start the session
     *
     * @return void
     */
    private function startSession()
    {
        if(session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }
        
    }
    
    /**
     * Retrieves a value in the Session
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return void
     */
    public function get(string $key, $default = NULL)
    {
        $this->startSession();
        if(array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }

        return $default;
    }
    
    /**
     * Add a value in the Session
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function set(string $key, $value)
    {
        $this->startSession();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Delete a Session value
     *
     * @param  mixed $key
     * @return void
     */
    public function delete(string $key)
    {
        $this->startSession();
        unset($_SESSION[$key]);
    }
}