<?php

namespace App\Controllers\Admin;

use Core\Auth;
use Core\Controller;
use Core\View;
use App\Models\Users;

class Register extends Controller
{
    public function before()
    {
        if (isset($_SESSION['auth'])) {
            header("Location: /php-mvc");
        }
    }

    public function registerAction()
    {
        View::renderTemplate('Auth/register.html', [
            'validate' => parent::$validateError,
        ]);
    }

    public function validateData()
    {
        parent::validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed:confirmed_password',
        ], $_POST);

        if (!empty(parent::$validateError)) {
            $this->registerAction();
        } else {
            $this->userCreate($_POST);
        }
    }

    protected function userCreate($data)
    {
        $name = $data['name'];
        $email = $data['email'];
        $password = md5($data['password']);

        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password');";
        Users::query($query);

        $query = "SELECT id FROM users ORDER BY id DESC LIMIT 1";
        $id = Users::query($query);

        $_SESSION['auth'] = true;
        $_SESSION['auth_id'] = $id[0]['id'];

        header("Location: /php-mvc/admin/posts");
    }
}
