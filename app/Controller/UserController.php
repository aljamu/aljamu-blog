<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Post;
use App\Model\User;
use flight\database\SimplePdo;
use flight\Engine;

class UserController
{
    /** @var Engine */
    private Engine $app;

    /** @var SimplePdo */
    private $db;

    /**
     * Constructor
     */
    public function __construct(Engine $app, SimplePdo $db)
    {
        $this->app = $app;
        $this->db = $db;
    }

    public function index(): void
    {
        $user = new User($this->db);
        $users = $user->with('posts')->orderByColumn('id', 'DESC')->findAll();

        $this->app->render('users/index', [
            'users' => $users,
        ]);
    }
}
