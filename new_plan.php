<?php
		
	ob_start();
	session_start();
	
	$mustlogin = false;
	$pageTitle = "New Plan";
	$pageDescription = "";
	
	include_once("Base.php");
    
    if ((isset($_GET['login_type']) && $_GET['login_type'] != "")) {
		$_SESSION['login_type'] = $_GET['login_type'];
	}
	
	if ((isset($_GET['bp_user_id']) && $_GET['bp_user_id']!= "")) {
		$bp_user_id = $_GET['bp_user_id'];

        if (!isset($_SESSION['bp_user_id']) || $_SESSION['bp_user_id'] == "") {
            $_SESSION['bp_user_id'] = $bp_user_id;
        }
	}
	else if (isset($_SESSION['bp_user_id']) && $_SESSION['bp_user_id']) {
		$bp_user_id = $_SESSION['bp_user_id'];
	}
	else {
		header('location: /signin');
		die();
	}
	//set trial session
	$_SESSION['trial']	= isset($_GET['t'])?$_GET['t']:0;
	$_SESSION['commission']	= isset($_GET['commission'])?$_GET['commission']:0;
	
	
	global $newPlanHome;
	$newPlanHome = true;
	$newPlanForm_start_year = date("Y");
	/*
	$folder01 =  "TOSIn";
	$accessMode = "w";
	$writeFolder = new writeToFile();
	
	echo $writeFolder->wrteToFile2($folder01, "", $accessMode);
	*/
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
	
	
	/*-----------------------------------------------
		 Change this when the user logs in
	------------------------------------------------*/
		
		
		
		
		$_SESSION['bp_user_id'] = $bp_user_id;
	/*-----------------------------------------------*/
	
	
	/******************************************************
	* 			IF POST Is New Business Plan 
	*******************************************************/
	if(isset($_POST['create_plan']))
	{
		$form_validation ="validate_business_plan_register_form"; require(VALIDATE_FORM);
		
		if (empty($outputMsg))
		{
			$table = BUSINESS_PLAN;
			$registerNow->startRegProcess($table);

			// if Business id exist
			if((int)$registerNow->businessPlanId > 0)
			{
				$_SESSION['bp_user_id'] = $bp_user_id; 
				$global_func->redirect(executive_summary_url."?bp=".$registerNow->businessPlanId);	
			}
		}
		else 
		{
			$registerNow->allmsgs = $outputMsg;
			$registerNow->color = $color;
		}
		
	}
	
?>
<?php include(TOP);?>
 

          <section class="clearfix" id="content-container">	
    		<div class="clearfix" id="main-wrap" >
             
			 
			 <!--Star Left panel-->
                     <div id="column_2">
                        <p class="intro-text">To start a new business plan, just provide the details requested below. We will use this information to suggest which sections to include in your plan outline.</p>
                            <p class="intro-text">Not sure which options to choose?<br><a href="http://www.practicepro.co.uk/contact" target="_blank">Get help here</a></p>
                        <div class="optional-container" style="display: none;">
                            <a class="optional-toggle" href="javascript:void(0);">
                                <h3>Optional Settings</h3>
                                <h4>Adjust language, date format, financial details, and other plan settings.</h4>
                                <span>Show Optional Settings</span>
                            </a>
                            <div class="optional" style="display: none;">
                                <h3>Optional Settings Coming Soon!</h3>
                                <span class="clear"></span>
                            </div>
                            <span class="clear"></span>
                        </div>
                    </div>
                   
            
             <div class="page_content_right sub-content">
          
		        <!-- Box -->         
        
      <div class="dim-action-active-page" id="content">
      
      
      
	 <p> <?php  $registerNow->DisplayAllMsgs('','');  ?></p>
<form id="newPlanForm" name="newPlanForm" method="post" class="no-enter" action="" target="">
		
     <!--   <input type="hidden" name="javax.faces.FormSignature" value="O4h+hRUiTDcdfLshNxwORmamZzE=">-->
                <div id="content" class="settings-container" style="min-height: 1px; "><div id="newPlanForm:planCreationForm">
                <div id="column_1">
                    <h3>Plan Name</h3>
                    <p>This name will appear on each page of your business plan. It also helps identify the plan on the list of plans created on your account.</p>
                    <ul class="plan-name settings">
                        <li class="text">
                            <input id="newPlanForm:plan_name" type="text" name="newPlanForm:plan_name" value="<?php echo $dummy_bp_name;?>" maxlength="60" size="60">
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

                                            <span class="title">Start Date:</span></label>
<select id="newPlanForm:start-month" name="newPlanForm:start-month" size="1" style="float: left">	
                                            
        <?php	
            foreach($defaultMonthist as $eachMonth)
            {
                ?>	
                <option value='<?php echo $eachMonth; ?>'><?php echo $eachMonth; ?></option>
            
            <?php }?>
    
    
</select>
                                                                            



                                           <label for="newPlanForm:start-year"> <span class="title">of</span></label>
                                           
                                           <input id="newPlanForm:start-year" type="text" name="newPlanForm:start-year" value="<?php echo $newPlanForm_start_year; ?>" maxlength="4" onblur="A4J.AJAX.Submit('newPlanForm',event,{'control':this,'similarityGroupingId':'newPlanForm:j_id212','parameters':{'newPlanForm:j_id212':'newPlanForm:j_id212','ajaxSingle':'newPlanForm:start\x2Dyear'} ,'actionUrl':'/create\x2Dplan'} )" size="4" class="valid">
                                           <span xmlns="http://www.w3.org/1999/xhtml" id="newPlanForm:j_id213" class="rich-message error"><span class="rich-message-label"></span></span>
                                    </li>
                                 </ul>
                           </div>
                            <br/><br/>
                           <input name="bp_user_id" type="hidden" value="<?php echo $bp_user_id; ?>"   /> 
                          
                                <span class="button-cap">  
                                	<span class="create">
                           				<button name="create_plan" type="submit" class="update_page"  >Create Plan</button>
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
