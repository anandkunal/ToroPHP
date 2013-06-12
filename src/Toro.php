<?php

class Toro
{

    // used in ToroUtil::url_for to resolve handlers urls
    private static $_routes;

    // and the method to return the routes
    public static function getRoutes()
    {
        return self::$_routes;
    }

    // route convenience tokens
    public static function getTokens()
    {
        return array(
            ':string' => '([a-zA-Z]+)',
            ':number' => '([0-9]+)',
            ':alpha'  => '([a-zA-Z0-9-_]+)'
        );  
    }

    public static function serve($routes)
    {
        self::$_routes = $routes;

        ToroHook::fire('before_request');

        $request_method = strtolower($_SERVER['REQUEST_METHOD']);
        $path_info = '/';
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : ((isset($_SERVER['ORIG_PATH_INFO']) and $_SERVER['ORIG_PATH_INFO'] !== "/index.php") ? $_SERVER['ORIG_PATH_INFO'] : $path_info);
        $discovered_handler = null;
        $regex_matches = array();

        if (isset($routes[$path_info])) {
            $discovered_handler = $routes[$path_info];
        }
        else if ($routes) {
            $tokens = self::getTokens();

            foreach ($routes as $pattern => $handler_name) {
                $pattern = strtr($pattern, $tokens);
                if (preg_match('#^/?' . $pattern . '/?$#', $path_info, $matches)) {
                    $discovered_handler = $handler_name;
                    $regex_matches = $matches;
                    break;
                }
            }
        }

        if ($discovered_handler && class_exists($discovered_handler)) {
            unset($regex_matches[0]);
            $handler_instance = new $discovered_handler();

            if (self::is_xhr_request() && method_exists($discovered_handler, $request_method . '_xhr')) {
                header('Content-type: application/json');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Cache-Control: post-check=0, pre-check=0', false);
                header('Pragma: no-cache');
                $request_method .= '_xhr';
            }

            if (method_exists($handler_instance, $request_method)) {
                ToroHook::fire('before_handler');
                call_user_func_array(array($handler_instance, $request_method), $regex_matches);
                ToroHook::fire('after_handler');
            }
            else {
                ToroHook::fire('404');
            }
        }
        else {
            ToroHook::fire('404');
        }

        ToroHook::fire('after_request');
    }

    private static function is_xhr_request()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}

class ToroHook
{
    private static $instance;

    private $hooks = array();

    private function __construct() {}
    private function __clone() {}

    public static function add($hook_name, $fn)
    {
        $instance = self::get_instance();
        $instance->hooks[$hook_name][] = $fn;
    }

    public static function fire($hook_name, $params = null)
    {
        $instance = self::get_instance();
        if (isset($instance->hooks[$hook_name])) {
            foreach ($instance->hooks[$hook_name] as $fn) {
                call_user_func_array($fn, array(&$params));
            }
        }
    }

    public static function get_instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new ToroHook();
        }
        return self::$instance;
    }
}

class ToroUtil
{
    /* 
     * Tries to return the url for a handler if the handler exists.
     * If the route of this handler was defined using parameters
     * then an array with the parameters in the same order is expected.
     *
     * example:
     *
     * Toro::serve(array(
     *     "/" => "IndexHandler",
     *     "/hello/:alpha" => "HelloHandler",
     *     "/test/this" => "TestHandler",
     * ));
     * 
     * ToroUtil::url_for("IndexHandler") would return "/"
     * ToroUtil::url_for("HelloHandler", array("test")) would return "/hello/test"
     *
     * but
     *
     * ToroUtil::url_for("HelloHandler") will not return because it's missing the
     * :alpha parameter
     * 
     * and
     *
     * ToroUtil::url_for("TestHandler") would return "/test/this"
     *
     * This is because routes can change so it is better
     * to define them only in one place (DRY) 
     *
     */

    public static function url_for($handler, $params = array())
    {
        $tokens = Toro::getTokens();
        $routes = Toro::getRoutes();

        foreach ($routes as $pattern => $handler_name) 
        {
            if ($handler_name == $handler)
            {

                /* convert the tokens like :string to regex like ([a-zA-Z]+) */
                $pattern = strtr($pattern, $tokens);
                /* find all the regex parameters in the route pattern */
                preg_match('/\(.*\)/', $pattern, $regs);
                /* 
                 * replace all the regex parameters with the parameters received
                 * to construct the url
                 */
                $url = str_replace($regs, $params, $pattern);
                /* 
                 * test that the route pattern matches the resulting url
                 * to validate that the correct number and type of parameters
                 * were received
                 */
                
                if (preg_match('#^/?' . $pattern . '/?$#', $url))
                {
                    return $url; 
                }
            }
        }
    }
}