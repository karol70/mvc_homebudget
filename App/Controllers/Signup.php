<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Signup extends \Core\Controller
{

    
    public function registerAction()
    {
        View::renderTemplate('Signup/register.html');
    }

     public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {

            $user->sendActivationEmail();

            $this->redirect('/signup/success');

        } else {

            View::renderTemplate('Signup/register.html', [
                'user' => $user
            ]);

        }
    }
	public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }
	public function activateAction()
    {
        User::activate($this->route_params['token']);

        $this->redirect('/signup/activated');
    }

 
    public function activatedAction()
    {
        View::renderTemplate('Signup/activated.html');
    }
}
   

