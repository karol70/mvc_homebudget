  

	var incomessum = parseFloat(document.getElementById("incomessum").innerText);
	var expensessum = parseFloat(document.getElementById("expensessum").innerText);
	
	
	
	
	if(incomessum > expensessum)
	{
		var diff = incomessum - expensessum;
		document.getElementById("balance").innerHTML = "Bilans: "+diff+"PLN  Gratulacje. Świetnie zarządzasz finansami!";
		document.getElementById("balance").style.backgroundColor = "green";
	}
	else if(incomessum < expensessum)
	{
		var diff = expensessum - incomessum ;
		document.getElementById("balance").innerHTML = "Bilans: -"+diff+"PLN  Uważaj, wpadasz w długi!";
		document.getElementById("balance").style.backgroundColor = "red";
	}


