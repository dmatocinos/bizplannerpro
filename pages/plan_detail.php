<?php
	ob_start();
	session_start();

    $pageTitle = "Plan Details";
	$mustlogin = false;
	$pageDescription = "";
	
	
	
	if ((isset($_GET['bp_user_id']) && $_GET['bp_user_id']!= "")) {
		$bp_user_id = $_GET['bp_user_id'];
	}
	else if (isset($_SESSION['bp_user_id']) && $_SESSION['bp_user_id']) {
		$bp_user_id = $_SESSION['bp_user_id'];
	} 
	else {
		header('location: /public/auth/signin');
		die();
	}
	
	
	
	include_once("../Base.php");
	
	global $newPlanHome;
	$newPlanHome = true;
	$newPlanForm_start_year = date("Y");
	
	
	
	
?>
<?php
	
	/*************************************
	*		VARIABLES
	***************************************/
	$dummy_bp_name = "";
	$bp_name = "";
    $bp_strategy = "";
    $bp_generic = "";
	
	$registerNow = new register_lib();
		
	$currentYear = date('Y');
	$currentMonth = date('M');
	$defaultDetails = new employee_lib();
	$defaultMonthist = $defaultDetails->twelveMonthsSetting($currentYear, $currentMonth);
	
	$objexp = new expenditure_lib();
	
	/*-----------------------------------------------
		 Change this when the user logs in
	------------------------------------------------*/
		
		
	
		$_SESSION['bp_user_id'] = $bp_user_id;
		
		$planData = $registerNow->getBusinessPlan();
		
		if($planData) {
			
			
			$startmonth		= $planData['bp_financial_start_date'];
			$tmp			= explode(' ', $startmonth);
			
			$startmonth		=  $registerNow->getLongMonth($tmp[0]);
			$newPlanForm_start_year		= $tmp[1];
			
			$oldPlanDate = $planData['bp_financial_start_date'];
			
		}
		
	/*-----------------------------------------------*/
	
	
	/******************************************************
	* 			IF POST Is New Business Plan 
	*******************************************************/
	if(isset($_POST['update_plan']))
	{
		$form_validation ="validate_business_plan_register_form"; require(VALIDATE_FORM);
		
		if (empty($outputMsg))
		{
			
			
						
			$table = BUSINESS_PLAN;
			$registerNow->updateData($table,'');

			// if Business id exist
			if((int)$registerNow->businessPlanId > 0)
			{
				$_SESSION['bp_user_id'] = $bp_user_id; 
				//$global_func->redirect(plan_detail.php);
			}
			
			
			$planData = $registerNow->getBusinessPlan();
			
			if($planData) {
					
					
				$startmonth		= $planData['bp_financial_start_date'];
				$tmp			= explode(' ', $startmonth);
					
				$startmonth		=  $registerNow->getLongMonth($tmp[0]);
				$newPlanForm_start_year		= $tmp[1];
				
				$newPlanDate = $planData['bp_financial_start_date'];
				
				//update majorpurchases dates				
				$objexp = new expenditure_lib();
				$objexp->updateMajorPurchaseDates($oldPlanDate, $newPlanDate);
				
				
				
					
			}
			
			
			
			
		}
		else 
		{
			$registerNow->allmsgs = $outputMsg;
			$registerNow->color = $color;
		}
		
	}
	
	
	
?>
<?php include(TOP2);?>
 

          <section class="clearfix" id="content-container">	
    		<div class="clearfix" id="main-wrap" >
             <!--Star Left panel-->
              <?php include_once(LEFTPANEL);?>
                   
            
             <div class="page_content_right sub-content">
          
		        <!-- Box -->         
        
      <div class="dim-action-active-page" id="content" style="padding: 10px;">
      
      
      
	 <p> <?php  $registerNow->DisplayAllMsgs('','');  ?></p>
<form id="newPlanForm" name="newPlanForm" method="post" class="no-enter" action="" target="">
		
     <!--   <input type="hidden" name="javax.faces.FormSignature" value="O4h+hRUiTDcdfLshNxwORmamZzE=">-->
                <div id="content" class="settings-container" style="min-height: 1px; "><div id="newPlanForm:planCreationForm">
                <div id="column_1">
                    <h3>Plan Name</h3>
                    <p>This name will appear on each page of your business plan. It also helps identify the plan on the list of plans created on your account.</p>
                    <ul class="plan-name settings">
                        <li class="text">
                            <input id="newPlanForm:plan_name" type="text" name="newPlanForm:plan_name" value="<?php echo $planData['bp_name'];?>" maxlength="60" size="60">
                        </li>
                    </ul>


			  <!--
                    <h3>Business Stage</h3>
                    <h4>How far along is your business idea? Is your company already up and running?</h4>
         
       
           <div class="radioListContainer">
               <ul id="businessStageRadioButtonGroup_radioList" class="settings">
                       <li class="option active">
                           <label for="radio_STARTUP">
                               <input name="businessStageRadioButtonGroupgroup" type="radio" id="radio_STARTUP" checked="true" value="STARTUP"><span class="title">Startup</span>
                               <span class="description"></span>
                           </label>
                       </li>
                    
                       <li class="option">
                           <label for="radio_ONGOING">
                               <input name="businessStageRadioButtonGroupgroup" type="radio" id="radio_ONGOING" value="ONGOING"><span class="title">Existing Business</span>
                               <span class="description"></span>
                           </label>
                       </li>
                      
               </ul>
               <div class="hiddenSelect">
               <table style="display:none">
	<tbody><tr>
<td>
<input type="radio" checked="checked" name="newPlanForm:businessStageRadioButtonGroup:j_id156" id="newPlanForm:businessStageRadioButtonGroup:j_id156:0" value="STARTUP">

<label for="newPlanForm:businessStageRadioButtonGroup:j_id156:0"> Startup</label></td>
<td>
<input type="radio" name="newPlanForm:businessStageRadioButtonGroup:j_id156" id="newPlanForm:businessStageRadioButtonGroup:j_id156:1" value="ONGOING" >
<label for="newPlanForm:businessStageRadioButtonGroup:j_id156:1"> Existing Business</label></td>
	</tr>
</tbody></table>

<script type="text/javascript">//<![CDATA[
 {
	var selector = "#businessStageRadioButtonGroup_radioList li.option input";
	try {
		selector = eval("#businessStageRadioButtonGroup_radioList li.option input");
	} catch (e) {}
	jQuery(selector).click(function() { selectUnderlyingRadio(this); });
}
//]]></script>
    
               </div>
           </div><!---end .radioListContainer
-->

<br/><br/>
                            <h3>Start Date</h3>
                            <p>When do you expect to start executing your business plan? Make your best guess. This will be the first month in your financials etc.</p>
                            <div id="newPlanForm:startDateContainer" class="startDateContainer">
                                <ul class="settings">
                                    <li class="select option not-generic">
                                          <input  value="false" name="generic" type="hidden" id="not-use-generic" checked="checked"><label for="newPlanForm:start-month">

                                            <span class="title" style="float: left">Start Date:</span></label>
<select id="newPlanForm:start-month" name="newPlanForm:start-month" size="1" style="float: left">	
                                            
        <?php	
            foreach($defaultMonthist as $eachMonth)
            {
                ?>	
                <option value='<?php echo $eachMonth; ?>'  <?php echo ($eachMonth!=$startmonth?"":"selected='selected'"); ?>><?php echo $eachMonth; ?></option>
            
            <?php }?>
    
    
</select>
                                                                            



                                           <label for="newPlanForm:start-year" style="float: left"> <span class="title">&nbsp;of&nbsp;&nbsp;</span></label>
                                           
                                           <input id="newPlanForm:start-year" type="text" name="newPlanForm:start-year" value="<?php echo $newPlanForm_start_year; ?>" maxlength="4" size="4" class="valid" style="float: left">
                                           <span xmlns="http://www.w3.org/1999/xhtml" id="newPlanForm:j_id213" class="rich-message error"><span class="rich-message-label"></span></span>
                                    </li>
                                 </ul>
                           </div>
                            <br/><br/>
                            
                            
                           
                            
                           <input name="bp_user_id" type="hidden" value="<?php echo $bp_user_id; ?>"   /> 
                           <input name="businessStageRadioButtonGroupgroup" type="hidden" value=""   />
                          
                                <span class="button-cap">  
                                	<span class="update">
                           				<button name="update_plan" type="submit" class="update_page"  >Update Plan</button>
                             		</span>
                                </span>
                          
   						</div></div>
                    </div>
                   
                
	</form> 
     <span class="clear"></span>
    
    </div>
    
                         <div class="x-clear"></div>
                       </div><!-- End #widgets-container--> 
                       
                       </div> <!-- End Box -->
                    <span class="clear"></span>
        	</div><!-- END of page_content_right-->
	</div><!-- END main-wrap -->
  
</section><!-- END content-container -->
<?php include_once(BOTTOM2);?>
