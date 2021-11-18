<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Auth;
use \App\Flash;
use \App\Models\showExpenses;

class Expense extends \Core\Controller
{
	public function newAction()
    {
        View::renderTemplate('Menu/addExpense.html');
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
	
	public function isLimitAction()
	{
		$limit = new Expenses($_POST);
		$limit->checkLimit();

	}
	
	public function LimitAmountAction()
	{
		$limit = new Expenses($_POST);
		$limit->getLimitAmount();
	}
	public function LimitActiveAction()
	{
		$limit = new Expenses($_POST);
		$limit->getLimitActive();
	}
	
	public function showExpensesAction()
	{
		showExpenses::showAllExpenses($_POST);
	}
	
	public function deleteExpenseAction()
	{
		if(showExpenses::deleteExpense($_POST))
		{
			View::renderTemplate('Menu/showBalance.html');
			Flash::addMessage('Usunięto wybraną transakcję');			
		}
	}
	
	public function setExpenseIdAction()
	{
		showExpenses::setExpenseId($_POST);
	}
	
	
	public function editExpenseAction()
	{
			View::renderTemplate('Menu/editExpense.html',[
			'selectedExpenses'=>showExpenses::getExpenseData()
			]);
			
	}
	
	public function updateAction()
	{
		$expense = new Expenses($_POST);
		if($expense->updateExpense())
		{
			Flash::addMessage('Zmiany w wybranej transacji zostały zapisane');
			View::renderTemplate('Menu/showBalance.html');			
		}
		else
		{
			Flash::addMessage('Niepoprawne dane', Flash::WARNING);

            View::renderTemplate('Menu/editExpense.html',[
                'expense' => $expense
            ]);
		}
	}
}