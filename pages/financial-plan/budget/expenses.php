
<div class="page-body">
    <div class="intro-block expanded">
                <div class="widget-content">
                    <h3>List your company's expenses</h3>
                    <p>Get started on your budget by adding your projected expenses below. Expenses like these are all tax deductible and will affect your profits. Be sure not to add any major purchases with long-lasting value here. (We will deal with those later, since they are not immediately tax deductible.) If your company is just getting started, be sure to include any one-time or short-term startup expenses in the early months as you get up and running.</p>
                    <p></p>
                    <h4>Personnel Expenses</h4>
                    <div class="personnel-expenses">
                        <p>The Salary and Employee Related Expenses lines are included on the Budget table. To edit them, <a href="<?php echo fplan_personal_url;?>">&nbsp;&nbsp;go to the Personnel table</a>.</p>
                    </div>
                </div>
            </div> <!--end .intro-block-->
             <?php 
            if(isset($_GET['add']) and ($_GET['add'] == "new_expenditure"))
            {
                //When a new expenditure is added display it in an edit form
                $getExpenditureId = $expenditure->maxEmployeeId;
                                                
                include_once('edit_expenditure_form.php');
            }
            elseif(isset($_GET['edit_expenditureID']))
            {
                //When edit button is clicked load the edit form
                $getExpenditureId = $_GET['edit_expenditureID'];
                include_once('edit_expenditure_form.php');
            }?>
            
             <p> <?php  $expenditure->DisplayAllMsgs('','');  ?></p>
             <?php include_once('all_expenses.php');?>
            <div class="page-footer">
                <div class="left"> <form class="add_new_emplyee" method="post">
                                        <input name="new_expenditure_name" value="<?php echo $new_expenditure_name;?>"  type="text"
                                         onfocus="if(this.value=='<?php echo $dummy_new_expenditure_name;?>') this.value='';" 
                                    onblur="if(this.value=='') this.value='<?php echo $dummy_new_expenditure_name;?>';"
                                         />
                                        <button type="submit" name="submit_expenditure">Add Expenditure</button>
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
