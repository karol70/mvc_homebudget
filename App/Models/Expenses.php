<?php

namespace App\Models;

use PDO;
use App\Auth;

class Expenses extends \Core\Model
{
	public $faults = [];
	
	public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
				
	public static function getAll()
	{
		if($user = Auth::getUser())
		{
			$userId = $user->id;
		
			$db = static::getDB();

            $stmt = $db->query("SELECT name FROM expenses_category_assigned_to_users WHERE user_id ='$userId'");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
		}
	}
	
	public function validate()
	{
			
						
			$this->amount = str_replace(',','.',$this->amount);
			
			if ($this->amount == '')
			{
				$this->faults[] = 'Wprowadź kwotę';
			}
		
			if (!is_numeric($this->amount))
			{
				$this->faults[] = 'Kwota może zawierać jedynie cyfry';
			}
						
			$dot = '.';
			$isThere = strpos($this->amount, $dot);
			
			if($isThere == true)
			{
				$amountPart = explode(".",$this->amount);
				
				if(strlen($amountPart[0])>6)
				{
					$this->faults[] = 'Maksymalna liczba cyfr przed przecinkiem wynosi 6';
				}	
				
				if(strlen($amountPart[1])>2)
				{
					$this->faults[] = 'Maksymalna liczba cyfr po przecinku wynosi 2';
				}
			}
			if($isThere == false)
			{
				if(strlen($this->amount)>6)
				{
					$this->faults[] = 'Maksymalna liczba cyfr kwoty całkowitej wynosi 6';
				}
			}
			
			
			
			if($this->category == NULL)
			{
				$this->faults[] = 'Wybierz kategorię';
			}
			
			
		
	}
	public function save()
    {
		$this->validate();
		
		if (empty($this->faults)) 
		{
			if($user = Auth::getUser())
			{
				$userId = $user->id;
				
				$db = static::getDB();
				$stmt = $db->query("SELECT id FROM expenses_category_assigned_to_users WHERE user_id = '$userId' AND name ='$this->category'");
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$catid = $results[0];
				$catid = $catid['id'];
				
				$db = static::getDB();
				$stmt = $db->query("SELECT id FROM payment_methods_assigned_to_users WHERE user_id = '$userId' AND name ='$this->method'");
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$methodid = $results[0];
				$methodid = $methodid['id'];
				var_dump($methodid);
				
			
				$sql = "INSERT INTO expenses(user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment) 
				VALUES( :userId, :catid, :methodid, :amount, :date, :comment)";
				
				$db = static::getDB();
				$stmt = $db->prepare($sql);

				$stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
				$stmt->bindValue(':catid', $catid, PDO::PARAM_STR);
				$stmt->bindValue(':methodid', $methodid, PDO::PARAM_STR);
				$stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);          
				$stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
				$stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

				return $stmt->execute();
			}
		}
		else
		{
			return false;
		}
	}
}