<?php

namespace App\Controller;

use App\Model\UserManager;

/**
 * Class UserController
 */
class UserController extends AbstractController
{


    /**
     * Display user listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $userManager = new UserManager();
            $users = $userManager->selectAll();
            return $this->twig->render('User/index.html.twig', ['users' => $users]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display user informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $userManager = new UserManager();
            $user = $userManager->selectOneWithDetails($id);
            return $this->twig->render('User/show.html.twig', ['user' => $user]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display user edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $userManager = new UserManager();
            $user = $userManager->selectOneWithDetails($id);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user = [
                    'id' => $id,
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'address' => $_POST['address'],
                    'role_id' => $_POST['role_id'],
                ];
                
                $userManager->update($user);
                header('Location:/user/index/');
            }
    
            return $this->twig->render('User/edit.html.twig', [
                'user' => $user,
            ]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display specie creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $userManager = new UserManager();
            $users = $userManager->selectAll();
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userManager = new UserManager();
                $user = [
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'address' => $_POST['address'],
                    'role_id' => $_POST['role_id'],
                ];
                $userManager->insert($user);
                header('Location:/user/index/');
            }
    
            return $this->twig->render('User/add.html.twig', [
                'users' => $users
            ]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Handle user deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $userManager = new UserManager();
            $userManager->delete($id);
            header('Location:/user/index');
        } else {
            header('Location:/home/index');
        }
    }
}
