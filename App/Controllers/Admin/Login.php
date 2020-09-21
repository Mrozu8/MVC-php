<?php

namespace App\Controllers\Admin;

use App\Models\Users;
use Core\Controller;
use Core\View;

class Login extends Controller
{
    public function before()
    {
        if (isset($_SESSION['auth'])) {
            header("Location: /php-mvc");
        }
    }

    public function loginAction()
    {
        View::renderTemplate('Auth/login.html', [
            'validate' => parent::$validateError,
        ]);
    }

    public function validateData()
    {
        parent::validate([
            'email' => 'required',
            'password' => 'required',
        ], $_POST);

        if (!empty(parent::$validateError)) {
            $this->loginAction();
        } else {
            $this->userCheck($_POST);
        }
    }

    protected function userCheck($request)
    {
        $email = $request['email'];
        $password = md5($request['password']);
        $query = "SELECT id FROM users WHERE email = '$email' AND password = '$password' LIMIT 1";
        $response = Users::query($query);

        if (!empty($response)) {
            $_SESSION['auth'] = 1;
            $_SESSION['auth_id'] = $response[0]['id'];
            header("Location: /php-mvc/admin/posts");
        } else {
            parent::$validateError['email'] = [
                'exists' => 'This user does not exist',
            ];
            $this->loginAction();
        }
    }

    public function logoutAction()
    {
        session_destroy();
        header("Location: /php-mvc");
    }
}
