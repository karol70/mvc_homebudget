<?php

namespace App\Models;

use PDO;
use App\Auth;

class PaymentMethods extends \Core\Model
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

            $stmt = $db->query("SELECT * FROM payment_methods_assigned_to_users WHERE user_id ='$userId'");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
		}
	}
	
	
}