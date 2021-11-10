function deleteExpenseFunction(id)
{
	if(confirm("Czy na pewno chcesz usunąć wybraną transakcję?"))
	{
		$(document).load("/expense/deleteExpense",{
				id: id
			});
		window.location.reload(true);
	}
}