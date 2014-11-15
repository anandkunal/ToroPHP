<?php
namespace Toro;

class Hook
{
    /**
     * The Hook instance.
     *
     * @var \Toro\Hook
     */
    private static $instance;

    /**
     * The registered hooks.
     *
     * @var array
     */
    private $hooks = [];

    /**
     * Prevent the class from being instantiated directly by making its constructor private.
     *
     * @return void
     */
    private function __construct()
    {
        //
    }

    /**
     * Prevent the class from being cloned.
     *
     * @return void
     */
    private function __clone()
    {
        //
    }

    /**
     * Add a hook.
     *
     * @param  string    $name
     * @param  callable  $fn
     * @return void
     */
    public static function add($name, $fn)
    {
        (self::getInstance())->hooks[$name][] = $fn;
    }

    /**
     * Fire hooks for the specified name.
     *
     * @param  string  $name 
     * @param  string  $params 
     * @return void
     */
    public static function fire($name, $params = null)
    {
        $instance = self::getInstance();

        if (isset($instance->hooks[$name])) {
            foreach ($instance->hooks[$name] as $fn) {
                call_user_func_array($fn, array(&$params));
            }
        }
    }

    /**
     * Get an instance of the Hook class.
     *
     * @return \Toro\Hook
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Hook();
        }

        return self::$instance;
    }
}
