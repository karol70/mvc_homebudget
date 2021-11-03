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
			Flash::addMessage("Błąd", Flash::WARNING);
			View::renderTemplate('Menu/settings.html');
		}
		
	}
	
	public function setLimitAction()
	{
		$var = $_SESSION['category'];
		Flash::addMessage("$var", Flash::WARNING);
		View::renderTemplate('Menu/settings.html');
	}
   
}