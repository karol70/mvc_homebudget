<?php

namespace App\Models;

use PDO;
use App\Auth;


class showIncomes extends \Core\Model
{

	public static function showAllIncomes()
	{
		if($user = Auth::getUser())
		{
			$userId = $user->id;
			if (isset( $_POST['categoryName']))
			{
				$cat = $_POST['categoryName'];
				$db = static::getDB();
				
				$sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id ="."$userId"." AND name ="."'$cat'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($results as $result)
				{
					$catId = $result['id'];
				}			
				if (isset($_SESSION['datefrom']) && isset($_SESSION['dateto']) )
				{
					$datefrom = $_SESSION['datefrom'];
					$dateto =$_SESSION['dateto'] ;
				
				
					$db = static::getDB();
					$sql = "SELECT * FROM incomes WHERE user_id = $userId AND income_category_assigned_to_user_id= $catId AND date_of_income BETWEEN '$datefrom' AND '$dateto'" ;
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}

echo'			
						<table class ="table  bg-success table-striped table-bordered border-dark" cellpadding ="2" border =2  >
						<thead class="thead-dark">
						<th class ="text-center ">Lp.</th>
						<th class ="text-center ">KATEGORIA</th>
						<th class ="text-center ">Kwota [PLN]</th>
				
						<th class ="text-center ">Data</th>
						</thead>';
						$number = 1;
						foreach($results as $result)
					{
						$id = $result['id'];
echo'					<tr class="table-success table-bordered border-dark">
						<td class ="text-center fw-bold">'."$number".'</td>
						<td class ="text-center fw-bold">'."$cat".'</td>
						<td class="text-center  fw-bold">'.$result["amount"].'</td>						
						<td class="text-center  fw-bold">'.$result["date_of_income"].'</td>';
echo'					<td class="text-center  fw-bold"><button class="deleteIncome btn" onclick="deleteIncomeFunction('."$id".')" value="';
echo 						$id;
echo' 					"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
						 <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
						</svg></button></td>
						</tr>';
						$number++;
					}
				
			}
		}
	}
	
	public static function deleteIncome()
	{
		if($user = Auth::getUser())
		{
			$userId = $user->id;
			if (isset( $_POST['id']))
			{
				$id = $_POST['id'];
				$db = static::getDB();
				
				$sql = "DELETE FROM incomes WHERE id = $id AND user_id = $userId";
				$stmt = $db->prepare($sql);
				return $stmt->execute();		 
		}
			}
	}
	
}