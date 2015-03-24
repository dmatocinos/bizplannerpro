<?php
	
	// Define variables and instantiate Class
	$dummy_new_expenditure_name = "Add New Expenditure";
	$new_expenditure_name = $dummy_new_expenditure_name;
	$expenditure = new expenditure_lib();
	$incomeTaxRate =  $expenditure->incomeTaxRate;
	
	
	// IF add employee is clicked
	if(isset($_POST['submit_expenditure']))
	{
		$form_validation ="validate_new_expenditure_form"; require("../form_validate.php");
		
		if (empty($outputMsg))
		{
			if($expenditure->createNewExpenditure($new_expenditure_name))
			{
				// reset the form data
				$new_expenditure_name = $dummy_new_expenditure_name;
				
				//commented out feb 15, 2014
				$newExpenditureUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?budget=_expenditure&add=new_expenditure&t=" . time();
				//$newExpenditureUrl = $_SERVER['PHP_SELF']."?budget=_expenditure&add=new_expenditure";
				
					
				$global_func->redirect($newExpenditureUrl);
			}
		}
		else 
		{
			$expenditure->allmsgs = $outputMsg;
			$expenditure->color = $color;
		}
	} 
	// Update Income Tax Rate
	elseif(isset($_POST['expenseBudget:input-taxrate']))
	{
		$budgetIncomeTaxRate  = (int)$_POST['expenseBudget:input-taxrate'];
		$bizPlanId = (int)$_SESSION['bpId'];
		
		if($expenditure->BispokeUpdateBizPlan($budgetIncomeTaxRate, $bizPlanId))
		{
			$newExpenditureUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?budget=_expenditure&pgIndex=2";
			$global_func->redirect($newExpenditureUrl);
		}
	}
	
	
	
?>
      <style>
	#widgets-container{
		padding-top:0;
	}
	.widget-page-header h2 {padding-left:0;}
	
	#widgets-container .nav li{
		list-style: none;
		margin-left: 0px;
		
	}
</style>
    <!--GOOD FOR THE POP OUT-->
	<script type="text/javascript" src="<?php echo BASE_URL;?>/js/widgetPagesPersonnel.js" ></script>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL;?>/css/widgetPages.css" />

     <h1>
        <span class="title" id="chapterName"><?php echo $pageTitle; ?> Table</span>
    </h1>
    <div class="widget-page-header">
        <h2><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="backtoplan">Back to Outline</a></h2>
    </div>
                        
    <div class="tableBuilder">
           
            
            <ul class="nav">
                    <li>
                        <a href="#expenses" class="active">
                            <span class="num">
    							1</span>
                            <span class="label">Expenses</span>
                            <span class="clear"></span>
                        </a>
                    </li>
                    
                    <li >
                        <a href="#major_purchase">
                            <span class="num">
   							 2</span>
                            <span class="label" style="width: 100px;">Major Purchases </span>
                            <span class="clear"></span>
                        </a>
                    </li>
                    <li >
                        <a href="#income_tax">
                            <span class="num">
   							 3</span>
                            <span class="label" style="width: 90px;">Income Taxes </span>
                            <span class="clear"></span>
                        </a>
                    </li>
            </ul>
            
            
            
            <div class="x-clear"></div>
            
            <div class="pages">
                <div class="page">
                	
                	<?php include_once("expenses.php");?>
                	
            	</div><!--end of page-->
             	<div class="page">             		
             	 	<?php include_once("major_purchase.php");?>
             	 	
             	</div><!--end of page-->
             	<div class="page">
             	 	<?php include_once("income_tax.php");?>
             	</div><!--end of page-->
            </div><!--end of pages-->
         </div><!--end .tableBuilder-->
        
      
		
		
		<script type="text/javascript">
            //<![CDATA[
            Ext.onReady(function () {
                bpo.widgetPage.personnel.init("Personnel Table");
                bpo.timer.page('widget', 'edit', 'Personnel Budget');
            });
            //]]>
        </script>
  </div><!--end #content-->
</div></div>