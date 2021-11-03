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
				$count = $stmt->rowCount();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
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
			  <th scope="col">Kategoria</th>
			</tr>
		 </thead>
		 <tbody>';
		$number = 1;
					foreach($results as $result)
					{
						
						echo '<tr>';
						echo '<th scope="row">'.$number.'</th>';
						echo '<td>'.$result['name'].'</td>';						
						echo '<tr>';

						$number++;						
					}	
				echo '</tbody>';
				echo '</table>';
		echo '</div>';
			}
			
			
			
			if($_SESSION['category']=="expenses_category_")
			{
				echo '<button type="button" class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#limit" >Ustaw limit</button> ';
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
										<label for="limit" class="form-label"> Wybierz kategorię: </label>	
											<select id="limit" class="form-control">';
												foreach($results as $result)
												{
													echo '<option  class="p-2" value="';
													echo $result['name'];
													echo'">';
													echo $result['name'];
													echo '</option>';								
												}	
											echo '</select>';
											
echo'										<label for="limit" class="form-label"> Kwota: </label>
											<input type="text" id="limit" class="form-control" required/>
									</div>
									</div>
								<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
										<input type="submit" class="btn btn-success" value="Ustaw limit"/>
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
	
}

