<?php

namespace Core;

use App\Models\Users;
use mysql_xdevapi\Exception;

abstract class Controller
{
    protected $route_params = [];
    static public $validateError = [];


    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }

    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller ". get_class($this));
        }
    }

    protected function before()
    {

    }

    protected function after()
    {

    }


//    ===== validate ======

    static public function validate($array, $data)
    {

        foreach ($array as $key => $val) {
            $rules = explode('|', $val);

            foreach ($rules as $rule) {
                if (strpos($rule, ':') !== false) {
                    $action = explode(':', $rule)[0];
                    self::$action($key, $data, $rule);
                } else {
                    self::$rule($key, $data);
                }
            }
        }
    }


//    =================== validate rule ======================

    private static function required($name, $data)
    {
        if (!isset($data[$name]) || empty($data[$name]) || $data[$name] == '' || $data[$name] == null) {
            self::$validateError[$name] = [
                'required' => "Field $name is required."
            ];
        } else {
            return true;
        }
    }

    private static function confirmed($name, $data, $rule)
    {
        $rule = explode(':', $rule);
        if ($data[$name] !== $data[$rule[1]]) {
            self::$validateError[$name] = [
                'confirmed' => 'The passwords must be confirmed.'
            ];
        }
    }

    private static function unique($name, $data, $rule)
    {
        $rule = explode(':', $rule)[1];
        $email = $data[$name];

        $query = "SELECT 1 FROM $rule WHERE $name = '$email' LIMIT 1";
        $response = Users::query($query);

        if (!empty($response)) {
            self::$validateError[$name] = [
                'unique' => "Field $name must be unique."
            ];
        }
    }
}
