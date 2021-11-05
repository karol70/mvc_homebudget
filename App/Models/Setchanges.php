<?php

namespace App\Models;

use PDO;
use App\Auth;

class Setchanges extends \Core\Model
{			
	public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
	
	public function isCategoryExist($newCategoryName)
	{
		if($user = Auth::getUser())
		{
			$userId = $user->id;
			$name = $_SESSION['category'];
		
			$db = static::getDB();
				
			$sql = "SELECT * FROM" ." $name"."assigned_to_users WHERE user_id ="."$userId";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
			foreach($results as $result)
			{
				if($result['name'] == $newCategoryName)
				return false;
			}
			return true;
		}				
	}
		
	
	public function addCategory()
	{
		$this->add = strtolower($this->add);
		$this->add = ucfirst($this->add);
		
		if($this->isCategoryExist($this->add))
		{
			if($user = Auth::getUser())
			{
				$userId = $user->id;
				$name = $_SESSION['category'];		
				
				$sql = "INSERT INTO $name"."assigned_to_users(user_id, name) 
					VALUES( :userId, :category)";
					
					$db = static::getDB();
					$stmt = $db->prepare($sql);

					$stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
					$stmt->bindValue(':category', $this->add, PDO::PARAM_STR);
					
					return $stmt->execute();
			}
		}
		else
		{
			return false;
		}		
	}
	
	public function getCategoryId($categoryName)
	{
		if($user = Auth::getUser())
		{
			$userId = $user->id;
			$name = $_SESSION['category'];
		
			$db = static::getDB();
				
			$sql = "SELECT * FROM" ." $name"."assigned_to_users WHERE user_id ="."$userId"." AND name ="."'$categoryName'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($results as $result)
			{
				return $result['id'];
			}			
		}
	}
	
	public function isTransactionExist($id)
	{
		$name = $_SESSION['category'];
		if($name=="expenses_category_")
		{
			$table = 'expenses';
			
			$db = static::getDB();				
			$sql = "SELECT * FROM expenses WHERE expense_category_assigned_to_user_id ="."'$id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (empty($results))
			{
				return false;
			}
			else 
				return $results;
			
		}
		
		if($name=="incomes_category_")
		{
			$table = 'incomes';
						
			$db = static::getDB();				
			$sql = "SELECT * FROM incomes WHERE income_category_assigned_to_user_id ="."'$id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (empty($results))
			{
				return false;
			}
			else 
				return $results;
		}
		
		if($name=="payment_methods_")
		{
			$table = 'expenses';
						
			$db = static::getDB();				
			$sql = "SELECT * FROM expenses WHERE payment_method_assigned_to_user_id ="."'$id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (empty($results))
			{
				return false;
			}
			else 
				return $results;
		}
	}
	
	public function deleteCategory()
	{
		$_SESSION['catId'] = $this->getCategoryId($this->delete);
		
		$catId = $_SESSION['catId'];
		
		if($this->isTransactionExist($catId) == false)
		{
			$this->deleteCategoryFromCategories($catId);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function deleteCategoryFromCategories($id)
	{
			$name = $_SESSION['category'];	
			
			$db = static::getDB();				
			$sql = "DELETE FROM" ." $name"."assigned_to_users WHERE id ="."'$id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
	}
	
	public function deleteExistCategory()
	{
		$catId = $_SESSION['catId'];
		if($this->deleteCat == "delete")
		{
			$this->deleteCategoryFromTransactions();
			$this->deleteCategoryFromCategories($catId);
			return 1;
		}
		else if($this->deleteCat == "transfer")
		{
			$this->transferTransaction();
			return 2;
		}
		else
			return 3;
		
	}
	
	public function deleteCategoryFromTransactions()
	{		
			$name = $_SESSION['category'];
			if($name=="expenses_category_")
			{
				$table = "expenses";
				$col = "expense";
			}
			if($name=="incomes_category_")
			{
				$table = "incomes";
				$col = "income";
			}
			$id = $_SESSION['catId'];
			$db = static::getDB();				
			$sql = "DELETE FROM "."$table"." WHERE "."$col"."_category_assigned_to_user_id ="."'$id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
	}
	
	public function transferTransaction()
	{
		
		$name = $_SESSION['category'];
		$categoryId = $this->getCategoryId("Inne");
		$id = $_SESSION['catId'];	
			if($name=="expenses_category_")
			{
				$sql = "UPDATE expenses SET expense_category_assigned_to_user_id ="."'$categoryId'"." WHERE expense_category_assigned_to_user_id ="."'$id'";			
			}
			
			if($name=="incomes_category_")
			{							
				$sql = "UPDATE incomes SET income_category_assigned_to_user_id ="."'$categoryId'"." WHERE income_category_assigned_to_user_id ="."'$id'";
			}
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->execute();
	}
	
	public function setLimit()
	{
		
		if($this->validate())
		{
			if($user = Auth::getUser())
			{
				$userId = $user->id;
				
				$sql = "UPDATE expenses_category_assigned_to_users SET expense_limit="."'$this->limitamount'"." WHERE name="."'$this->categorylimit'"." AND user_id="."'$userId'";
				$db = static::getDB();
				$stmt = $db->prepare($sql);
				$stmt->execute();
				return true;
			}
		}
		else return false;
		
	}
	public function unsetLimit()
	{
		if($this->categorylimit !== NULL)
		{
			if($user = Auth::getUser())
			{
				$userId = $user->id;
				
				$sql = "UPDATE expenses_category_assigned_to_users SET expense_limit=NULL WHERE name="."'$this->categorylimit'"." AND user_id="."'$userId'";
				$db = static::getDB();
				$stmt = $db->prepare($sql);
				$stmt->execute();
				return true;
			}
		}
		else return false;
	}
	
	public function validate()
	{
			$_POST['limitamount'] = str_replace(',','.',$_POST['limitamount']);
			
			if ($this->limitamount == '')
			{
				return false;
			}
		
			if (!is_numeric($this->limitamount))
			{
				return false;
			}
						
			$dot = '.';
			$isThere = strpos($this->limitamount, $dot);
			
			if($isThere == true)
			{
				$amountPart = explode(".",$this->limitamount);
				
				if(strlen($amountPart[0])>6)
				{
					return false;
				}	
				
				if(strlen($amountPart[1])>2)
				{
					return false;
				}
			}
			if($isThere == false)
			{
				if(strlen($this->limitamount)>6)
				{
					return false;
				}
			}
			
			if($this->categorylimit == NULL)
			{
				return false;
			}
			return true;
	}
	
}