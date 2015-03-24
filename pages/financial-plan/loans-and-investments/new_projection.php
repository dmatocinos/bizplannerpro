<?php
	
	// Define variables and instantiate Class
	$dummy_new_loanInvest_name = "Add New Investment / Loan";
	$new_loanInvest_name = $dummy_new_loanInvest_name;
	$cashProjection = new loansInvestments_lib();
	
	 
	// IF add employee is clicked
	if(isset($_POST['submit_balance_projection']))
	{
		$form_validation ="validate_new_loanInvest_form"; require("../form_validate.php");
		
		if (empty($outputMsg))
		{
			if($cashProjection->createNewLoanOrInvestment($new_loanInvest_name))
			{
				// reset the form data
				$new_loanInvest_name = $dummy_new_loanInvest_name;
				
				$newloanInvestUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?projection=_loan_invest&add=new_projection";
				
				$global_func->redirect($newloanInvestUrl);
			}
		}
		else 
		{
			$cashProjection->allmsgs = $outputMsg;
			$cashProjection->color = $color;
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
            <div class="pages">
                <div class="page">
                	<div class="page-body">
                    <div class="intro-block expanded">
                                <div class="widget-content">
                                    <h3>Are you planning to get loans, investments, or other funding?</h3>
                                    <p>Adding funding to your plan is easy. Just walk through the steps below. If you already know the details of 
                                    your funding sources, the table builder will automatically calculate your payments and update the financials 
                                    appropriately. Not sure yet where the money is going to come from? That's fine too. Just choose "Other" as 
                                    the funding type, then enter the amounts you need and a rough guess at the payback details. Beyond funding,
                                     this table builder is also useful for adding loans to pay for major purchases, such as a vehicle or capital improvement.</p>
                                    
                                    <p>If you are not planning on any loans or other funding, you can use the Chapter Setup view to remove
                                     this section from your plan. You can always add it back if your needs change later.</p>
                                   
                                </div>
                            </div> <!--end .intro-block-->
               			     <?php 
						 	if(isset($_GET['add']) and ($_GET['add'] == "new_projection"))
							{
								//When a new expenditure is added display it in an edit form
								$getLoanInvestId = $cashProjection->maxEmployeeId;
								include_once('edit_projection_form.php');
                            }
							elseif(isset($_GET['edit_loanInvestID']))
							{
								//When edit button is clicked load the edit form
								$getLoanInvestId = $_GET['edit_loanInvestID'];
								include_once('edit_projection_form.php');
							}?>
                            
                             <p> <?php  $cashProjection->DisplayAllMsgs('','');  ?></p>
							 <?php include_once('all_projections_loans_and_investements.php');?>
                            <div class="page-footer">
                                <div class="left"> <form class="add_new_emplyee" method="post">
                                						<input name="new_loanInvest_name" value="<?php echo $new_loanInvest_name;?>"  type="text"
                                                         onfocus="if(this.value=='<?php echo $dummy_new_loanInvest_name;?>') this.value='';" 
                									onblur="if(this.value=='') this.value='<?php echo $dummy_new_loanInvest_name;?>';"
                                                         />
                                                         <input type="hidden" name="type_of_funding" value="Loan" />
                                    					<button type="submit" name="submit_balance_projection">Add Projection</button>
                                    				</form>
                                 </div>
                                <div class="right">
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>"  class="button button-gray"><span class="button-cap"><span>I'm Done</span></span></a>
                                   
                                </div>
                            </div>    
                     </div><!--end .page-body-->
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
