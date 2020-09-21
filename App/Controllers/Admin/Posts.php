<?php

namespace App\Controllers\Admin;

use App\Models\Post;
use Core\Controller;
use Core\View;

class Posts extends Controller
{
    public function before()
    {
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header("Location: /php-mvc");
        }
    }


    public function indexAction()
    {
        $query = "SELECT * FROM posts ORDER BY id DESC";
        $posts = Post::query($query);

        $validate = parent::$validateError;

        View::renderTemplate('Posts/admin-panel.html', [
            'posts' => $posts,
            'validate' => $validate,
        ]);
    }

    public function createAction()
    {
        parent::validate([
            'title' => 'required',
            'content' => 'required',
        ], $_POST);

        if (!empty(parent::$validateError)) {
            $this->indexAction();
        } else {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $id = $_SESSION['auth_id'];

            $query = "INSERT INTO posts (user_id, title, content) VALUES ('$id', '$title', '$content')";
            Post::query($query);
        

            header('Location: /php-mvc/admin/posts');
        }
    }

    public function editAction()
    {
        $id = $this->route_params['id'];
        $query = "SELECT *  FROM posts WHERE id = '$id'";
        $response = Post::query($query);
        $validate = parent::$validateError;

        View::renderTemplate('Posts/edit.html', [
            'post' => $response[0],
            'validate' => $validate,
        ]);
    }

    public function updateAction()
    {
        parent::validate([
            'title' => 'required',
            'content' => 'required',
        ], $_POST);

        if (!empty(parent::$validateError)) {
            $this->editAction();
        } else {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $id = $this->route_params['id'];

            $query = "UPDATE posts SET title = '$title', content = '$content' WHERE id = '$id'";
            Post::query($query);

            header('Location: /php-mvc/admin/'.$id.'/post-edit');
        }
    }


    public function deleteAction()
    {
        $id = $this->route_params['id'];
        $query = "DELETE FROM posts WHERE id = '$id'";
        Post::query($query);

        header('Location: /php-mvc/admin/posts');
    }
}
