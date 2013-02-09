<?php

namespace Toro;

/**
 * This file is part of the Toro routing package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Toro\Hook as ToroHook;

/**
 * Toro.
 *
 * Main Toro router
 * 
 */
class Router
{

    /**
     * Serve request from routes
     *
     * @param array  $routes   Associative array of routes (path => handler as string)
     *
     * @return none
     */
    public static function serve(Array $routes)
    {
        ToroHook::fire('before_request');

        $request_method = strtolower($_SERVER['REQUEST_METHOD']);
        $path_info = '/';
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : $path_info);
        $discovered_handler = null;
        $regex_matches = array();

        if (isset($routes[$path_info])) {
            $discovered_handler = $routes[$path_info];
        }
        else if ($routes) {
            $tokens = array(
                ':string' => '([a-zA-Z]+)',
                ':number' => '([0-9]+)',
                ':alpha'  => '([a-zA-Z0-9-_]+)'
            );
            foreach ($routes as $pattern => $handler_name) {
                $pattern = strtr($pattern, $tokens);
                if (preg_match('#^/?' . $pattern . '/?$#', $path_info, $matches)) {
                    $discovered_handler = $handler_name;
                    $regex_matches = $matches;
                    break;
                }
            }
        }

        if ($discovered_handler) {
            self::resolve_handler($discovered_handler, $request_method, $regex_matches);
        } else {
            ToroHook::fire('404');
        }

        ToroHook::fire('after_request');
    }

    /**
     * Resolves the controller callable and calls its instance
     *
     * Override this function in your own router class to customize
     *
     * @param string  $discovered_handler   Handler class name
     * @param string  $request_method       The request method
     * @param array   $matches              Matches from route
     *
     * @return bool success or not
     */
    protected static function resolve_handler($discovered_handler, $request_method, Array $matches) 
    {
        unset($matches[0]);

        if(!class_exists($discovered_handler)) {
            ToroHook::fire('404');
            return false;
        }

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
            call_user_func_array(array($handler_instance, $request_method), $matches);
            ToroHook::fire('after_handler');
        }
        else {
            ToroHook::fire('404');
        }
    }

    /**
     * Checks if request made from XmlHttpRequest object
     *
     * @return bool
     */    
    private static function is_xhr_request()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}
