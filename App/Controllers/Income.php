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
			View::renderTemplate('Menu/showBalance.html');
			Flash::addMessage('Usunięto wybraną transakcję');
			
		}
	}
	
	public function setIncomeIdAction()
	{
		showIncomes::setIncomeId($_POST);
	}
	
	
	public function editIncomeAction()
	{
			View::renderTemplate('Menu/editIncome.html',[
			'selectedIncomes'=>showIncomes::getIncomeData()
			]);
			
	}
	
	public function updateAction()
	{
		$income = new Incomes($_POST);
		if($income->updateIncome())
		{
			Flash::addMessage('Zmiany w wybranej transacji zostały zapisane');
			View::renderTemplate('Menu/showBalance.html');			
		}
		else
		{
			Flash::addMessage('Niepoprawne dane', Flash::WARNING);

            View::renderTemplate('Menu/editIncome.html',[
                'income' => $income
            ]);
		}
	}
	
}