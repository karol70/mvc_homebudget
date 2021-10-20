<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Auth;
use \App\Flash;

class Expense extends \Core\Controller
{
	public function newAction()
    {
        View::renderTemplate('Menu/addIncome.html');
    }

    
    public function createAction()
    {
		$expense = new Expenses($_POST);
		
        if ($expense->save()) {

            Flash::addMessage('Dodano wydatek');

            $this->redirect('/menu/menu');

        } else {

            Flash::addMessage('Niepoprawne dane', Flash::WARNING);

            View::renderTemplate('Menu/addExpense.html',[
                'expense' => $expense
            ]);
                
        }
    }
}