<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Settings;
use \App\Models\Setchanges;
use \App\Auth;
use \App\Flash;

class Setting extends \Core\Controller
{
	public function newAction()
    {
        Settings::getAllCategories();
    }
	
	public function userSettingsAction()
    {
        Settings::viewUserSettings();
		
    }
	
	
	public function addCategoryAction()
	{
		$addCategory = new Setchanges($_POST);
		if($addCategory->addCategory())
		{
			Flash::addMessage("Dodano nową kategorię");
			View::renderTemplate('Menu/settings.html');
		}
		else
		{
			Flash::addMessage("Wprowadzona nazwa kategorii już istnieje, spróbuj ponownie", Flash::WARNING);
			View::renderTemplate('Menu/settings.html');
		}
	
	}
	
	public function deleteCategoryAction()
	{

		$deleteCategory = new Setchanges($_POST);
		if($deleteCategory->deleteCategory())
		{
			Flash::addMessage("Usunięto kategorię");
			View::renderTemplate('Menu/settings.html');
		}
		else
		{
			Flash::addMessage("W kategorii, którą chcesz usunąć są już zapisane wydatki!", Flash::WARNING);
			View::renderTemplate('Menu/deleteCategory.html');
		}
		
	}
	
	public function deleteCategoryIfExistAction()
	{
		
		$existCategory = new Setchanges($_POST);
		
		if($existCategory->deleteExistCategory() == 1 )
		{
			Flash::addMessage("Usunięto kategorię oraz zapisane w niej transakcje");
			View::renderTemplate('Menu/settings.html');
		}
		else if ($existCategory->deleteExistCategory() == 2 )
		{
			Flash::addMessage("Usunięto kategorię i przeniesiono transakcje w niej zapisane do kategorii 'Inne'");
			View::renderTemplate('Menu/settings.html');
		}
		else
		{
			Flash::addMessage("Operacja usunięcia kategorii została anulowana");
			View::renderTemplate('Menu/settings.html');
		}
		
	}
	
	public function setLimitAction()
	{
		if(isset($_POST['setOrUnsetLimit']))
		{
			$editCategory = new Setchanges($_POST);
			
			if($editCategory->setLimit())
			{
				Flash::addMessage("Ustawiono limit dla wybranej kategorii");
				View::renderTemplate('Menu/settings.html');
			}
			else
			{
				Flash::addMessage("Błędne dane, spróbuj ponownie", Flash::WARNING);
				View::renderTemplate('Menu/settings.html');
			}
		}
		if(empty($_POST['setOrUnsetLimit']))
		{
			$editCategory = new Setchanges($_POST);
			
			if($editCategory->unsetLimit())
			{
				Flash::addMessage("Zdjęto limit dla wybranej kategorii");
				View::renderTemplate('Menu/settings.html');
			}
			else
			{
				Flash::addMessage("Błędne dane, spróbuj ponownie", Flash::WARNING);
				View::renderTemplate('Menu/settings.html');
			}
		}
	}
	
	
	public function editAction()
	{
		View::renderTemplate('Setting/edit.html',[
			'user' => Auth::getUser()
		]);
	}
	
	public function updateAction()
	{
		$user = Auth::getUser();
		
		if($user->updateProfile($_POST))
		{
			Flash::addMessage("Zmiany zostały zapisane");
			View::renderTemplate('Menu/settings.html');
		}
		else
		{
			View::renderTemplate('Setting/edit.html',[
			'user' => Auth::getUser()
		]);
		}
	}
	
	public function deleteTransactionsAction()
	{
		$deleteTransaction = new Setchanges();
		if($deleteTransaction->deleteAllTransactions())
		{
			Flash::addMessage("Wszystkie transakcje zostały usunięte");
			View::renderTemplate('Menu/settings.html');
		}
		else
		{
			Flash::addMessage("Wystąpił błąd, spróbuj ponownie", Flash::WARNING);
			View::renderTemplate('Menu/settings.html');
		}
	}
	
	public function deleteAccountAction()
	{
		
		
		if(Setchanges::deleteAccount())
		{			
			Auth::logout();
			Flash::addMessage("Konto zostało usunięte");
			View::renderTemplate('Home/index.html');
		}
		else
		{
			Flash::addMessage("Wystąpił błąd, spróbuj ponownie", Flash::WARNING);
			View::renderTemplate('Home/index.html');
		}
			
		
	}
   
}