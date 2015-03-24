<?php
  /**---	UPDATED JUNE 10 2013	---**/
?>
<?php 
	$expenditure = new expenditure_lib();
	$employee = new employee_lib();
	$allExpDetails = $expenditure->getAllExpenditureDetails("", "", ""); // All Expenditures
	$allEmpDetails = $employee->getAllEmployeeDetails2("", "", ""); // All employees
?>
    
   
    <?php
        $counter = 0;
        $arraySummation = "";
        // Related Expenses calculation
        (int)$personalRelatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];
        $personalRelatedExpenseInPercentage = ($personalRelatedExpenses / 100);
        
        
        
        /*---------------------------------------------------------------------
            Employee Salary Calculation loop using the same counter and 
            array summation for both allEmployee and all exenditure
        ---------------------------------------------------------------------*/
        if($allEmpDetails)
		{
			foreach($allEmpDetails as $empDetails)
			{
				//$empDetails = number_format($empDetails);
				for($i=0; $i< count($empDetails['financial_status']); $i++)
				{
					 $arraySummation[$i][$counter]  = ($empDetails['financial_status'][$i]['total_per_yr']);
				 }
				 $counter = $counter+1;
			}
			
	        /*---------------------------------------------------------------------
                Calculate Employee Related Expenses 	
            ---------------------------------------------------------------------*/
          	foreach($allEmpDetails as $empDetails)
			{		
				for($i=0; $i< count($empDetails['financial_status']); $i++)
				{
					 $arraySummation[$i][$counter]  = ($personalRelatedExpenseInPercentage * $empDetails['financial_status'][$i]['total_per_yr']);
				}
				$counter = $counter+1;
			} 
           
		}// -----------	End of $allEmpDetails is true	-----------
        ?>
        
        
        <?php 
        /*---------------------------------------------------
            Expenditure  Calculation loop	
        /*-----------------------------------------------*/
       if($allExpDetails)
	   {
			foreach($allExpDetails as $expDetails)
			{?>
			   
				
				<?php 
					
					for($i=0; $i< count($expDetails['financial_status']); $i++)
					{
						 $arraySummation[$i][$counter]  = $expDetails['financial_status'][$i]['total_per_yr'];
					}
					$counter = $counter+1;
					 
			}// end foreach ?>
			
			
			<!------------------------------------------
				Total Expsenses
			 ------------------------------------------>
			<?php
			$y = 0;
			$allExpense = array();
			foreach($arraySummation as $sumOfAllExpenses)
			{
				$allExpense[$y] = array_sum($sumOfAllExpenses);
				$eachYear[$allExpDetails[0]['financial_status'][$y]['financial_year']] = $allExpense[$y];
				$y = $y+1;
			}
	   }// end of if $allExpDetails
		
 // Return $allExpense
 ?>
        