<?php

namespace App\Controller;

use App\Controller\MainController;
use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends MainController
{
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function loginMethod()
    {
        if (!empty($_POST)) {

            $user = ModelFactory::getModel('User')->readData($_POST['email'], 'email');

            if (password_verify($_POST['pass'], $user['pass'])) {

                $this->session->createSession(
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['admin']
                );

                $this->redirect('home');
            }
                 }
        return $this->twig->render('user/loginUser.twig');
    }

    public function logoutMethod()
    {
        $this->session->destroySession();

        $this->redirect('home');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createMethod()
    {
        if (!empty($_POST)) {
            $user = ModelFactory::getModel('User')->readData($_POST['email'], 'email');

            $data['pass']   = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $data['name']   = $_POST['name'];
            $data['email']  = $_POST['email'];
            $data['admin']  = false;

            ModelFactory::getModel('User')->createData($data);

            $this->redirect('home');
        }
        return $this->twig->render('user/createUser.twig');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateMethod()
    {
        if (!empty($_POST)) {

            $data['pass']   = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $data['name']   = $_POST['name'];
            $data['email']  = $_POST['email'];

            ModelFactory::getModel('User')->updateData(filter_input(INPUT_GET,'id'), $data);

            $this->redirect('home');
        }
        $user = ModelFactory::getModel('User')->readData(filter_input(INPUT_GET,'id'));

        return $this->twig->render('user/updateUser.twig', ['user' => $user]);
    }

    public function deleteMethod()
    {
        ModelFactory::getModel('User')->deleteData(filter_input(INPUT_GET,'id'));

        $this->redirect('home');
    }
}