<?php

namespace Core;

class View
{
	public static function render($view, $args = [])
	{
		extract ($args, EXTR_SKIP);
		
		$file = "../App/Views/$view";
		
		if(is_readable($file))
		{
			require $file;
		}
		else
		{
			throw new \Exception ("$file not found");
		}
		
	}
	
	public static function renderTemplate($template, $args = [])
	{
		static $twig = null;
		
		if($twig === null) 
		{
			$loader = new \Twig\Loader\FilesystemLoader('../App/Views');
			$twig = new \Twig\Environment($loader);
			$twig->addGlobal('current_user',\App\Auth::getUser());
			$twig->addGlobal('flash_messages',\App\Flash::getMessages());
			$twig->addGlobal('incomes',\App\Models\Incomes::getAll());
			$twig->addGlobal('expenses',\App\Models\Expenses::getAll());
			$twig->addGlobal('paymentmethods',\App\Models\PaymentMethods::getAll());
			$twig->addGlobal('userIncomes',\App\Models\Incomes::getAllUserIncomes());
			$twig->addGlobal('userExpenses',\App\Models\Expenses::getAllUserExpenses());
			$twig->addGlobal('selectedIncomes',\App\Models\ShowIncomes::getIncomeData());
			$twig->addGlobal('selectedExpenses',\App\Models\ShowExpenses::getExpenseData());
		}
		echo $twig->render($template, $args);
	}
	
	 public static function getTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
			$twig = new \Twig\Environment($loader);
            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
        }

        return $twig->render($template, $args);
    }
	
}