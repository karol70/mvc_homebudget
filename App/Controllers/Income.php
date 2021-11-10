<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;
use \App\Auth;
use \App\Flash;
use \App\Models\showIncomes;

class Income extends \Core\Controller
{
	public function newAction()
    {
        View::renderTemplate('Menu/income.html');
    }
   
    public function createAction()
    {
		$income = new Incomes($_POST);
		
        if ($income->save()) {

            Flash::addMessage('Dodano przychód');

            $this->redirect('/menu/menu');

        } else {

            Flash::addMessage('Niepoprawne dane', Flash::WARNING);

            View::renderTemplate('Menu/addIncome.html',[
                'income' => $income
            ]);
                
        }
    }
	public function showIncomesAction()
	{
		showIncomes::showAllIncomes($_POST);
	}
	public function deleteIncomeAction()
	{
		if(showIncomes::deleteIncome($_POST))
		{
			Flash::addMessage('Usunięto wybraną transakcję');
		}
	}
}