<?php
	$dummy_new_major_purchase_name = "Add New Major Purchase";
	$new_major_purchase_name = $dummy_new_major_purchase_name;
	
	
	// IF add employee is clicked
	if(isset($_POST['submit_major_purchase']))
	{
		$form_validation ="validate_new_major_form"; require("../form_validate.php");
		
		if (empty($outputMsg))
		{
			if($expenditure->createNewMajorPurchase($new_major_purchase_name))
			{
				// reset the form data
				$new_major_purchase_name = $dummy_new_major_purchase_name;
				
				$newMajorPurchaseUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?budget=_expenditure&add=new_major_purchase&pgIndex=1";
				
				$global_func->redirect($newMajorPurchaseUrl);
			}
		}
		else 
		{
			$expenditure->allmsgs = $outputMsg;
			$expenditure->color = $color;
		}
	} 

?>
<div class="page-body">
    <div class="intro-block expanded">
                <div class="widget-content">
                    <h3>List any major purchases with long-lasting value</h3>
                    <p>It is customary for major purchases that offer long-lasting value to be treated differently than regular expenses in the financials. The expenses you entered in the previous step here are typically used up within the period in which they are paid. Paying rent in January provides you with value in January but not in February or beyond. Its value is temporary. Buying a company work van in January, on the other hand, might result in a large cash outlay (or loan obligation) in January, but it will continue to provide value to you for years to come. This is the concept that accountants call "assets." We call them "major purchases" here, since that is a little easier to remember, but it's the same idea.</p>
                    <p></p>
                    <p>Note that depreciation is calculated automatically based on a plan option called Average Depreciation Period.</p>
                </div>
            </div> <!--end .intro-block-->
             <?php 
            if(isset($_GET['add']) and ($_GET['add'] == "new_major_purchase"))
            {
                //When a new expenditure is added display it in an edit form
               
                include_once('edit_majorPurchase_form.php');
            }
            elseif(isset($_GET['edit_new_majorPurchaseID']))
            {
                //When edit button is clicked load the edit form
                $getExpenditureId = $_GET['edit_majorPurchaseID'];
                include_once('edit_majorPurchase_form.php');
            }?>
            
             <p> <?php  $expenditure->DisplayAllMsgs('','');  ?></p>
             <?php include_once('all_major_purchases.php');?>
            <div class="page-footer">
                <div class="left"> 
                				<form class="add_new_emplyee" method="post">
                                        <input name="new_major_purchase_name" value="<?php echo $new_major_purchase_name;?>"  type="text"
                                         onfocus="if(this.value=='<?php echo $dummy_new_major_purchase_name;?>') this.value='';" 
                                    onblur="if(this.value=='') this.value='<?php echo $dummy_new_major_purchase_name;?>';"
                                         />
                                        <button type="submit" name="submit_major_purchase">Add a Major Purchase</button>
                                    </form>
                    <!--<a href="<?php echo $_SERVER['PHP_SELF']; ?>?personnel=_employee&&add=_new" class="button button-gray"><span class="button-cap">
                        <span>Add an Employee</span></span>
                     </a>
                     -->
                    
                     
                </div>
                <div class="right">
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>"  class="button button-gray"><span class="button-cap"><span>I'm Done</span></span></a>
                    <span class="button button-primary continue"><span class="button-cap"><span>Continue</span></span></span>
                </div>
            </div>    
     </div><!--end .page-body-->
