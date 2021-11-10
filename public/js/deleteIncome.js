function deleteIncomeFunction(id)
{
	if(confirm("Czy na pewno chcesz usunąć wybraną transakcję?"))
	{
		$(document).load("/income/deleteIncome",{
				id: id
			});
		window.location.reload(true);
	}
}