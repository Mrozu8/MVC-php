<?php
//
namespace App\Controllers;

use App\Models\Post;
use Core\Controller;
use Core\View;

class Home extends Controller
{
    public function indexAction()
    {
        $query = 'SELECT title, content, name FROM posts JOIN users on posts.user_id = users.id ORDER BY posts.created_at DESC';
        $posts = Post::query($query);

        View::renderTemplate('Home/index.html', [
            'posts' => $posts,
        ]);
    }
}



