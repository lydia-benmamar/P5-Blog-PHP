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

            $user = ModelFactory::getModel('User')->readData($this->post->getPostVar('email'), 'email');

            if (password_verify($this->post->getPostVar('pass'), $user['pass'])) {

                $this->session->createSession(
                    $user['id'],
                    $user['name'],
                    $user['image'],
                    $user['email']
                );

                $this->cookie->createAlert('Authentification réussie, bienvenue ' . $user['name'] .' !');

                $this->redirect('home');
            }
            $this->cookie->createAlert('Authentification échouée !');
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
        if (!empty($this->post->getPostArray())) {

            if (!empty($this->files->getFileVar('name'))) {
                $data['image'] = $this->files->uploadFile('img/user');
            }

            $data['pass']   = password_hash($this->post->getPostVar('pass'), PASSWORD_DEFAULT);
            $data['name']   = $this->post->getPostVar('name');
            $data['email']  = $this->post->getPostVar('email');

            ModelFactory::getModel('User')->updateData($this->get->getGetVar('id'), $data);
            $this->cookie->createAlert('Modification réussie de l\'utilisateur sélectionné !');

            $this->redirect('home');
        }
        $user = ModelFactory::getModel('User')->readData($this->get->getGetVar('id'));

        return $this->twig->render('user/updateUser.twig', ['user' => $user]);
    }

    public function deleteMethod()
    {
        ModelFactory::getModel('User')->deleteData($this->get->getGetVar('id'));
        $this->cookie->createAlert('Utilisateur définitivement supprimé !');

        $this->redirect('home');
    }
}