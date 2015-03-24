<?php
	
	// Define variables and instantiate Class
	$dummy_new_employee_name = "Add New Employee";
	$new_employee_name = $dummy_new_employee_name;
	$incomeTax = $_SESSION['bpRelatedExpensesInPercentage'];
	
	$employee = new employee_lib();
	
	 
	// IF add employee is clicked
	if(isset($_POST['submit_employee']))
	{
		$form_validation ="validate_new_employee_form"; require("../form_validate.php");
		
		if (empty($outputMsg))
		{
			if($employee->createNewEmployee($new_employee_name))
			{
				// reset the form data
				$new_employee_name = $dummy_new_employee_name;
				
				$newEmployeeUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?personnel=_employee&add=new_employee";
				
				$global_func->redirect($newEmployeeUrl);
			}
		}
		else 
		{
			$employee->allmsgs = $outputMsg;
			$employee->color = $color;
		}
	}
	// update burden rate
	else if(isset($_POST['update_burden_rate']))
	{
		$employeeBurdenRate  = (int)$_POST['personnel:employeeBurdenRate'];
		$bizPlanId = (int)$_SESSION['bpId'];
		
		if($employee->BispokeUpdateBizPlan($employeeBurdenRate, $bizPlanId))
		{
			$newEmployeeUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?personnel=_employee";
				
			$global_func->redirect($newEmployeeUrl);
		}
		
	}
	
	
	
?>


      
    <!--GOOD FOR THE POP OUT-->
	<script type="text/javascript" src="<?php echo BASE_URL;?>/js/widgetPagesPersonnel.js" ></script>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL;?>/css/widgetPages.css" />
    
     
             <h1>
                   <span class="title" id="chapterName"><?php echo $pageTitle; ?> Table</span>
            </h1>
            <div class="widget-page-header">
                <h2><a href="<?php echo $_SERVER['PHP_SELF'];?>" class="backtoplan">Back to Outline</a></h2>
            </div>
    
    <div class="tableBuilder">
            <h2 style="float: left;">Personnel Table</h2>
            <ul class="nav">
                    <li>
                        <a href="#expenses" class="active">
                            <span class="num">
    							1</span>
                            <span class="label">Personnel</span>
                            <span class="clear"></span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#burden">
                            <span class="num">
   							 2</span>
                            <span class="label" style="width: 110px;">Related Expenses</span>
                            <span class="clear"></span>
                        </a>
                    </li>
            </ul>
            <div class="x-clear"></div>
            
            <div class="pages">
                <div class="page">
                    <div class="page-body">
                        <div class="intro-block ">
                            <div class="widget-content">
                                <h3>List your current and planned employees</h3><p>This is where you will cover salaries and wages paid to your
                                 employees and independent contractors, including your own pay. Depending on how big your company is, you can list
                                  every employee by name or title, or you can group them into employee types or groups if that makes more sense. 
                                  Two things to keep in mind:</p><p>1. Don't include employee benefits and payroll taxes here. You'll handle that 
                                  in the next step.</p><p>2. Don't forget to pay yourself! Your accountant may have you listing your own salary 
                                  a different way for accounting purposes, but this is planning, and you need to include your own 
                                  compensation as part of your expenses.</p>
                                <div class="x-clear"></div>
                            </div>
                        </div>
                    
                    	
                        <?php 
						 	if(isset($_GET['add']) and ($_GET['add'] == "new_employee"))
							{
								//When a new employee is added display it in an edit form
								$getEmployeeId = $employee->maxEmployeeId;
								
								include_once('edit_empolyee_form.php');
                            }
							elseif(isset($_GET['edit_employeeID']))
							{
								//When edit button is clicked load the edit form
								$getEmployeeId = $_GET['edit_employeeID'];
								
								include_once('edit_empolyee_form.php');
								
							}?>
                            
                             <p> <?php  $employee->DisplayAllMsgs('','');  ?></p>
							 <?php include_once('all_employees.php');?>
                            <div class="page-footer">
                                <div class="left"> <form class="add_new_emplyee" method="post">
                                						<input name="new_employee_name" value="<?php echo $new_employee_name;?>"  type="text"
                                                         onfocus="if(this.value=='<?php echo $dummy_new_employee_name;?>') this.value='';" 
                									onblur="if(this.value=='') this.value='<?php echo $dummy_new_employee_name;?>';"
                                                         />
                                    					<button type="submit" name="submit_employee">Add an Employee</button>
                                    				</form>
                                    <!--<a href="<?php echo $_SERVER['PHP_SELF']; ?>?personnel=_employee&&add=_new" class="button button-gray"><span class="button-cap">
                                        <span>Add an Employee</span></span>
                                     </a>
                                     -->
                                    
                                     
                                </div>
                                <div class="right">
                                    <a href="<?php echo $_SERVER['PHP_SELF'];?>"  class="button button-gray"><span class="button-cap"><span>I'm Done</span></span></a>
                                    <span class="button button-primary continue"><span class="button-cap"><span>Continue</span></span></span>
                                </div>
                            </div>   
                    </div><!--end .page-body-->
           	   	</div><!--end of page-->
                <div class="page">
                    <div class="page-body">
                        <div id="" class="intro-block">
                            <div class="widget-content">
                                <h3>Enter your estimated rate for employee-related expenses</h3><p>Salaries and wages are far from the only expenses involved in having employees. 
                                Depending on your location, other employee-related expenses may include payroll taxes, worker's compensation insurance, health insurance, and 
                                other benefits and taxes.</p><p>These expenses need to be reflected in your plan. It's not necessary to try to predict these expenses in precise 
                                detail. Instead, business plans typically use what's called a "burden" rate. This is just a simple percentage of total employee compensation that
                                 is added to cover these related expenses. (Note that the burden rate does not apply to contract workers.)</p><p>In the profit and loss statement,
                                  you can see the calculated amount &mdash; total employee compensation multiplied by the burden percentage &mdash; on the "Employee
                                   Related Expenses" line.</p>
                            </div>
                        </div>
                        <div class="line-item">
                            <div id="personnel:j_id281" class="header">
                                <h3>Burden Rate</h3></div>
                                <div class="content">
                                    <div class="step overall-editor single-step" style="margin-bottom: 0px;">
                                        <div class="tax-rate-percent">
                                            <div class="num">1</div>
                                            <h4 class="label">Enter your estimated rate for employee-related expenses</h4>
                                            <div class="step-inner">
                                               <form class="plain_form" method="post">
                                                <input id="personnel:employeeBurdenRate" type="text" name="personnel:employeeBurdenRate" value="<?php echo $incomeTax;?>" class="" maxlength="5" />
                                                 
                            					<span class="percent" style="margin-top: 5px !important;">%</span>
                                                <span id="personnel:j_id289" class="rich-message">
                                                    <span class="rich-message-label"></span>
                                                </span>
                                              
                                                	<div class="x-clear"></div>
                                                    <br/>
                                                	<button type="submit" name="update_burden_rate">Update Burden Rate</button>
                                                </form>
                                            </div>
                                            <div class="x-clear"></div>
                                        </div>
                                    </div>
                                 </div>	
                            </div>
                        
                        <div id="personnel:j_id291" class="action-links" style="height: 24px;"></div>
                    </div>
                    <div class="page-footer">
                        <div class="left">
                            
                            <!--
                            <a href="javascript:void(0);" class="button button-gray show-preview disabled"><span class="button-cap">
                            <span>Show Preview</span></span></a>-->
                          </div>
                        <div class="right"><a href="<?php echo $_SERVER['PHP_SELF'];?>" class="button button-primary">
                                <span class="button-cap"><span>I'm Done</span></span></a>
                        </div>
                    </div>
                     <span class="clear"></span><br/>  
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
 
