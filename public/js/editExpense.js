function setExpenseIdFunction(id)
{
	if(confirm("Czy na pewno chcesz edytować wybraną transakcję?"))
	{
		$(document).load("/expense/setExpenseId",{
				id: id
			});
	}
}