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

            $stmt = $db->query("SELECT * FROM expenses_category_assigned_to_users WHERE user_id ='$userId'");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
		}
	}
	
	public static function getAllUserExpenses()
	{
		if($user = Auth::getUser())
		{
			$userId = $user->id;
			if (isset ($_SESSION['datefrom'])&&isset($_SESSION['dateto']))
			{
			$datefrom = $_SESSION['datefrom'];
			$dateto =$_SESSION['dateto'] ;
			}
			else
			{
			$datefrom = '';
			$dateto ='' ;
			}
			
		
			$db = static::getDB();

            $stmt = $db->query("SELECT name, SUM(amount) AS sum FROM expenses,expenses_category_assigned_to_users AS cat WHERE expenses.user_id = '$userId' AND cat.id = expenses.expense_category_assigned_to_user_id AND date_of_expense BETWEEN '$datefrom' AND '$dateto' GROUP BY name");
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
	
	public function isLimitSet($category)
	{
		if($user = Auth::getUser())
			{
				$userId = $user->id;
				$db = static::getDB();
				$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id ="."$userId"." AND name="."'$category'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach($results as $result)
				{
					return $result['expense_limit'];
				}
				return false;
			}
	}
	public function sumIfExpenseAdded()
	{
		$month = date("m");
		$year = date("Y");
		$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$dateFrom = $year."-".$month."-"."01";
		$dateTo = $year."-".$month."-".$days;
		if($user = Auth::getUser())
			{
				$userId = $user->id;
				$category = $this->categoryName;
				$db = static::getDB();
				$sql = "SELECT SUM(amount) AS sum FROM expenses,expenses_category_assigned_to_users AS cat WHERE expenses.user_id = '$userId'  AND cat.id = expenses.expense_category_assigned_to_user_id AND cat.name ='$category' AND date_of_expense BETWEEN '$dateFrom' AND '$dateTo'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($results as $result)
				{
					return $result['sum'];
				}
				return 0;
			}
		
	}
	public function checkLimit()
	{		
		$category = $this->categoryName;
		$limit = $this->isLimitSet($category);
		$amount = $this->amount;
		
		$amount = number_format($amount, 2, '.', '');
		if($limit != false)
		{
			$limit = number_format($limit, 2, '.', '');
			$expenseSum = $this->sumIfExpenseAdded();
			$expenseSum = number_format($expenseSum , 2, '.', '');
			if($limit >= $expenseSum  + $amount)
			{				
				$class = "bg-success";
			}
			if($limit < $expenseSum  + $amount)
			{
				
				$class = "bg-danger";
			}
				$diff = $limit - $expenseSum;
				$diff = number_format($diff , 2, '.', '');
				$sum = $expenseSum + $amount;
				$sum = number_format($sum , 2, '.', '');
				
echo 			"<div class='$class d-flex flex-row text-center rounded'>

				<div class='d-inline p-1 w-25'>
				<p>Limit: </p>$limit
				</div>
				
				<div class='d-inline p-1 w-25'>
				<p class='font-weight-bold'>Dotychczas wydano: </p>$expenseSum
				</div>
				
				<div class='d-inline p-1 w-25'>
				<p class='font-weight-bold'>Różnica: </p> $diff
				</div>
				
				<div class='d-inline p-1 w-25'>
				<p class='font-weight-bold'>Wydatki + wpisana kwota:</p> $sum
				</div>

				</div>";
		}
		
		
		
	}
}