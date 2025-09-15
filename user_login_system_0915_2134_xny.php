<?php
// 代码生成时间: 2025-09-15 21:34:01
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;
use Phalcon\Validation\Validator\StringLength as StringLengthValidator;
use Phalcon\Validation\Validator\Identical as IdenticalValidator;
use Phalcon\Flash\Session as Flash;
use Phalcon\Mvc\View;
use Phalcon\Di;
use Phalcon\Mvc\Model\Message as Message;

class AuthController extends Controller
{
    public function loginAction()
    {
        // Check if the request is sent by POST method
        if ($this->request->isPost()) {
            // Get the request parameters
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Initialize the flash session service
            $flash = $this->getFlash();

            // Validate the input
            $validation = new Validation();
            $validation->add('email', new PresenceOfValidator(array(
                'message' => 'Email is required.'
            )));
            $validation->add('email', new EmailValidator(array(
                'message' => 'Email is not valid.'
            )));
            $validation->add('password', new PresenceOfValidator(array(
                'message' => 'Password is required.'
            )));
            $validation->add('password', new StringLengthValidator(array(
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters.'
            )));

            // Check if the validation passes
            $messages = $validation->validate($this->request->getPost());
            if (count($messages)) {
                foreach ($messages as $message) {
                    $flash->error($message->getMessage());
                }
                return $this->forward('auth/login');
            }

            // Authenticate the user
            $user = Users::findFirst(
                array(
                    'conditions' => 'email = :email: AND active = 1',
                    'bind' => array('email' => $email)
                )
            );
            if (!$user) {
                $flash->error('Incorrect email or password.');
                return $this->forward('auth/login');
            }

            // Check if the password matches
            $hash = $user->password;
            if (!$this->security->checkHash($password, $hash)) {
                $flash->error('Incorrect email or password.');
                return $this->forward('auth/login');
            }

            // Start the session
            $this->session->start();
            $this->session->set('auth', array(
                'id' => $user->id,
                'name' => $user->name
            ));

            // Redirect to the dashboard
            return $this->response->redirect('index/index');
        }
    }

    public function logoutAction()
    {
        // Destroy the session
        $this->session->remove('auth');
        $this->session->destroy();

        // Redirect to the login page
        return $this->response->redirect('auth/login');
    }
}
