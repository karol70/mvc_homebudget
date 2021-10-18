<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

class Menu extends Authenticated
{
	protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }
	public function menuAction()
	{
	
		View::renderTemplate('Menu/menu.html');
	}
	public function incomeAction()
	{
	
		View::renderTemplate('Menu/addIncome.html');
	}
	public function expenseAction()
	{
	
		View::renderTemplate('Menu/addExpense.html');
	}
	public function balanceAction()
	{
	
		View::renderTemplate('Menu/showBalance.html');
	}
}