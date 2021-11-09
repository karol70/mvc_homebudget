<?php

namespace App\Models;

use PDO;
use App\Auth;

class Settings extends \Core\Model
{
	public static function getAllCategories()
	{
		if($user = Auth::getUser())
		{
			$userId = $user->id;
			if(isset($_POST['name']))
			{
				$name = $_POST['name'];	
				
				$db = static::getDB();
				
				$sql = "SELECT * FROM" ." $name"."assigned_to_users WHERE user_id ="."$userId";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$col = $stmt->columnCount();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				//$col = $results->columnCount();
				
				if($name=="expenses_category_"){
					$category = 'expenses';
				}
				if($name=="incomes_category_"){
					$category = 'incomes';
				}
				if($name=="payment_methods_"){
					$category = 'payment';
				}				
					$_SESSION['category'] = $name;
								
		
		echo ' <div class="bg-success p-4 w-75 mx-auto " style="--bs-bg-opacity: .5;">';
		echo '<table class="table text-light">';
echo'		<thead>
			<tr>
			  <th scope="col">Lp.</th>
			  <th scope="col">Kategoria</th>';
			  if($col == 4)
			  {
echo'			<th scope="col">Limit</th>';	  
			  }
echo'			</tr>
		 </thead>
		 <tbody>';
		$number = 1;
					foreach($results as $result)
					{
						echo '<tr>';
						echo '<th scope="row">'.$number.'</th>';
						echo '<td>'.$result['name'].'</td>';
						 if($col == 4)
						{
							if( $result['expense_limit'] !== NULL)
							{
								echo'<td>'.$result['expense_limit'].'</td>';
							}
							else
							{
								echo'<td>-</td>';
							}
						}
						echo '<tr>';

						$number++;						
					}	
				echo '</tbody>';
				echo '</table>';
		echo '</div>';
			}
			
			
			
			if($_SESSION['category']=="expenses_category_")
			{
				echo '<button type="button" class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#limit" >Ustaw lub zdejmij limit</button> ';
				echo '<button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#add"" >Dodaj kategorię</button>';
				echo '<button type="button" class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#delete" >Usuń kategorię</button> ';
			}
			if($_SESSION['category']=="incomes_category_")
			{
				echo '<button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#add"" >Dodaj kategorię</button>';
				echo '<button type="button" class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#delete" >Usuń kategorię</button> ';
			}
			if($_SESSION['category']=="payment_methods_")
			{
				echo '<button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#add"" >Dodaj kategorię</button>';
			}
				
echo'			<div class="modal fade text-dark" id="limit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Ustaw limit</h5>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="/setting/setLimit">
									<div class="modal-body">
										<div class="form-inline mx-auto">
										<label for="categorylimit" class="form-label"> Wybierz kategorię: </label>	
											<select id="categorylimit" name="categorylimit" class="form-control" required>';
												foreach($results as $result)
												{
													echo '<option  class="p-2" value="';
													echo $result['name'];
													echo'">';
													echo $result['name'];
													echo '</option>';								
												}	
											echo '</select>';
											
echo'										<div class="mt-2">
												<input class="form-check-input" name="setOrUnsetLimit" type="radio" value="set" id="setLimit" onclick="var input=
												document.getElementById('.'\'limitamount\''.'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}"/>
											  <label class="form-check-label" for="setLimit">
												Ustaw limit
											  </label>
											</div>
											<div class="mt-1 mb-2 ">
											  <input class="form-check-input" name="setOrUnsetLimit" type="radio" value="unset" id="deleteLimit" onclick="var input=
												document.getElementById('.'\'limitamount\''.'); if(this.checked){ input.disabled = true; input.focus();}else{input.disabled=true;}"/>
											  <label class="form-check-label" for="deleteLimit">
												Zdejmij limit
											  </label>
											</div>';
											  
echo'										<label for="limitamount" class="form-label"> Kwota: </label>
											<input type="text" id="limitamount" name="limitamount" class="form-control" disabled="disabled" required/>
									</div>
									</div>
								<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
										<input type="submit" class="btn btn-success" value="Wybierz"/>
								</div>
							</form>
						
						</div>
					</div>
				</div>';
				
			
echo'			<div class="modal fade text-dark" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Usuń kategorię</h5>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="/setting/deleteCategory">
									<div class="modal-body">
										<div class="form-inline mx-auto">
										
											<label for="delete" class="form-label"> Wybierz kategorię: </label>										
											<select id="delete" name="delete" class="form-select" aria-label="select">';
												foreach($results as $result)
												{
													if($result['name'] !== "Inne")
													{
														echo '<option  class=" p-2" value="';
														echo $result['name'];
														echo'">';
														echo $result['name'];
														echo '</option>';
													}													
												}	
											echo '</select>';
echo'									</div>
									</div>
								<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
										<input type="submit" class="btn btn-success" value="Usuń kategorię"/>
								</div>
							</form>
						
						</div>
					</div>
				</div>';
			
			
echo'			<div class="modal fade text-dark" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Dodaj kategorię</h5>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="/setting/addCategory">
									<div class="modal-body">
										<div class="form-inline mx-auto">
											<label for="add" class="form-label"> Nazwa nowej kategorii: </label>
											<input type="text" id="add" name="add" class="form-control" required/>
									</div>
								<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
										<input type="submit" class="btn btn-success" value="Dodaj kategorię"/>
								</div>
							</form>
						
						</div>
					</div>
				</div>';
			
		}
	}
	
	public static function viewUserSettings()
	{
		$user = Auth::getUser();


echo"	<div class='fw-bold d-flex mx-auto mt-3 p-2'>
			<div class='d-flex-column mx-5 text-success'>
				<p class='text-uppercase'> Dane użytkownika: </p>			
				<p>Nazwa użytkownika:<br/> $user->username </p>
				<p>Email:<br/> $user->email</p>
			</div>";
		
echo'   	<div class=" d-flex w-75">
				<div class=" d-flex-column ">
				<div class ="block">
					<a href="/setting/edit" type="button" class="btn btn-success mt-1 mb-2 w-100">Edytuj swoje dane</a> 
				</div>
				<div class ="block">
					<button type="button" class="btn btn-danger my-2 w-100" data-bs-toggle="modal" data-bs-target="#deleteIncomesAndExpenses" >Usuń wszystkie przychody i wydatki</button>
				</div>
				<div class ="block">
					<button type="button" class="btn btn-danger my-2 w-100" data-bs-toggle="modal" data-bs-target="#deleteAccount" >Usuń moje konto</button>
				</div>
			</div>
		</div>';
	
echo'			
							
			<div class="modal fade text-dark" id="deleteIncomesAndExpenses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Usuń wszystkie przychdy i wydatki</h5>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
								<div class="modal-body">
									<p> Czy na pewno chcesz usunąć wszystkie przychody i wydatki? </p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
									<a href="/setting/deleteTransactions" class="btn btn-warning" > Usuń </a>
								</div>
							
						
						</div>
					</div>
				</div>
				
			<div class="modal fade text-dark" id="deleteAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Usuń konto</h5>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							
									<div class="modal-body">
										<p> Czy na pewno chcesz usunąć swoje konto? </p>
									</div>
								<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
										<a href="/setting/deleteAccount" class="btn btn-warning">Usuń konto </a>
								</div>
							
						
						</div>
					</div>
				</div>';
		
	}
	
}

