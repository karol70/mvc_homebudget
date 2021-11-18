function setIncomeIdFunction(id)
{
	if(confirm("Czy na pewno chcesz edytować wybraną transakcję?"))
	{
		$(document).load("/income/setIncomeId",{
				id: id
			});

	
	}
}