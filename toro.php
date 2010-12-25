<?php

class InvalidRouteType extends Exception {}

// Provided by Danillo César de O. Melo
// https://github.com/danillos/fire_event/blob/master/Event.php
class ToroHook {
    private static $instance;
  
    private $hooks = array();
  
    private function __construct() { }
    private function __clone() { }
  
    public static function add($hook_name, $fn) {
        $instance = self::get_instance();
        $instance->hooks[$hook_name][] = $fn;
    }
  
    public static function fire($hook_name, $params = NULL) {
        $instance = self::get_instance();
        if (array_key_exists($hook_name, $instance->hooks)) {
            foreach ($instance->hooks[$hook_name] as $fn) {
                call_user_func_array($fn, array(&$params));
            }
        }
    }
  
    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new ToroHook();
        }
        return self::$instance;
    }
}

class ToroApplication {
    private $_handler_route_pairs = array();

    public function __construct($handler_route_pairs) {
        foreach ($handler_route_pairs as $pair) {
            if ($pair[1] == 'string' || $pair[1] == 'regex') {
                array_push($this->_handler_route_pairs, $pair);
            }
            else {
                throw new InvalidRouteType();
            }
        }
    }

    public function serve() {
        ToroHook::fire('before_request');
    
        $request_method = strtolower($_SERVER['REQUEST_METHOD']);
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $discovered_handler = null;
        $regex_matches = Array();
        $method_arguments = null;

        foreach ($this->_handler_route_pairs as $handler) {
            list($pattern, $pattern_type, $handler_name) = $handler;

            // Argument overrides (must be an array)
            if (isset($handler[3])) {
                $method_arguments = $handler[3];
            }
            else {
                $method_arguments = null;
            }

            if ($pattern_type == 'string' && $path_info == $pattern) {
                $discovered_handler = $handler_name;
                $regex_matches = array($path_info, preg_replace('/^\//', '', $path_info));
                break;
            }
            else if ($pattern_type == 'regex') {
                if (preg_match('/' . $pattern . '/', $path_info, $matches)) {
                    $discovered_handler = $handler_name;
                    $regex_matches = $matches;
                    break;
                }
            }
        }

        if ($discovered_handler && class_exists($discovered_handler)) {
            unset($regex_matches[0]);
            $handler_instance = new $discovered_handler();

            if (!$method_arguments) {
                $method_arguments = $regex_matches;
            }

            // XHR (must come first), iPad, mobile catch all
            if ($this->xhr_request() && method_exists($discovered_handler, $request_method . '_xhr')) {
                header('Content-type: application/json');
                header('Pragma: no-cache');
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                $request_method .= '_xhr';
            }
            else if ($this->ipad_request() && method_exists($discovered_handler, $request_method . '_ipad')) {
                $request_method .= '_ipad';
            }
            else if ($this->mobile_request() && method_exists($discovered_handler, $request_method . '_mobile')) {
                $request_method .= '_mobile';
            }

            ToroHook::fire('before_handler');
            call_user_func_array(array($handler_instance, $request_method), $method_arguments);
            ToroHook::fire('after_handler');
        }
        else {
            header('HTTP/1.0 404 Not Found');
            echo '404 Not Found';
            exit;
        }
    
        ToroHook::fire('after_request');
    }

    private function xhr_request() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    private function ipad_request() {
        return strstr($_SERVER['HTTP_USER_AGENT'], 'iPad');
    }

    private function mobile_request() {
        return strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPod') || strstr($_SERVER['HTTP_USER_AGENT'], 'Android') || strstr($_SERVER['HTTP_USER_AGENT'], 'webOS');
    }
}

class ToroHandler {
    public function __construct() { }

    public function __call($name, $arguments) {
        header('HTTP/1.0 404 Not Found');
        echo '404 Not Found';
        exit;
    }
}
