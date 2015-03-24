<?php
	if($getPayments)
	{

		$outgoingPercentage =  $getPayments[0]['percentage_purchase']; 
		$collectOutgoingPayment =  $getPayments[0]['days_make_payments']; 
	
	}
	else
	{
		$outgoingPercentage =  0; 
		$collectOutgoingPayment =  0; 
	}
	/*----------------------------------------------------------------------------------------------------
		EDIT / DELETE EMPLOYEE DETAILS
	*-----------------------------------------------------------------------------------------------------*/
	
	// if update button
	if(isset($_POST['update_outgoing_payment']))
	{
		
		$outgoingPercentage = $_POST['outgoingPercentage'];
		$collectOutgoingPayment = $_POST['collectOutgoingPayment'];
	
		
		if($cashFlow->updateOutgoingPayments($businessPlanId, $outgoingPercentage, $collectOutgoingPayment))
		{
			//$cashFlow->global_func->redirect($_SERVER['PHP_SELF']."?cashflow=payments");
			$cashFlow->allmsgs = "Outgoing Payments was updated. Please try again.";
			$cashFlow->color = "blue";	
		}
		
		else
		{
			$cashFlow->allmsgs = "Outgoing Payments could not be updated. Please try again.";
			$cashFlow->color = "red";
		}
	}
	
	/*-----------------------------------------------------------------------------------------------*/

?>


<style>

	.step-inner, .different-ammounts {
		display: block;
		float: left;
		width:750px;
		margin-left:53px;
	}
	.outgoing_payment{
		background-color:#c3c3c3;
		min-height:70px;
		width: 696px;
		border:5px dashed #e6e6e6;	
	}
	input#outgoingPercentage, input#collectOutgoingPayment{
		margin-top:11px;
		margin-left:20px;
		background-color:#c3c3c3;
		width:78px;
		float:left;
		font-size:40px;
		color: #504730;
		text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
		
		font-weight: bold;
		text-transform: uppercase;
		padding:0;
		border:0;
		
	}
	input#collectOutgoingPayment{
			
	}
	.percent{
		margin-top:11px;
		float:left;
		font-size:43px;
		color: #504730;
		text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
		font-weight: bold;
		background-color:#c3c3c3;
		width:45px;
		margin-left:0;
		margin-right:77px;
	}
	.dhtmlxSlider_ball{
		
		margin-top:30px;
		float:left;
		
		
	}
</style>

<p>&nbsp;</p>                                                    
<div id="personnel:j_id258">
    <div id="expense-budget-list-wrapper">
    	<div id="personnel:expense-budget-list" class="dim-action-expense-budget-list">
    <form method="post" action="" >	
        <div id="personnel:j_id266:expense-item" class="expense-item selected-expense">
            <div class="expense-budget-edit" rel="24fec7e6-b2f7-4a75-bfa9-787fe0cdb1df">
                <div class="item-header">
                    <h3>Outgoing payments</h3>
                   
                </div>
                <div class="expense-budget-entryMethod">
                    <div class="step expense-name">
                        <div class="num"> 1</div>
           
                        
                        <h4 class="label">What percentage of your purchases will be on credit?</h4>
                            
                           <div class="step-inner">
                        	<p>Select the approximate percentage of your company's purchases that will 
                            be billed for later payment, not paid up front.</p>
                           
                             						
                             <div class="outgoing_payment">
                             <input type="text" id="outgoingPercentage" name="outgoingPercentage" 
                             						value="<?php echo $outgoingPercentage;?>" />	<span class="percent">%</span>
							 <script>
                                    var slider23 = new dhtmlxSlider(null, {
                                        skin: "ball", //dhx_skyblue
                                        min: 0,
                                        max: 100,
                                        step: 5,
                                        size: 400,
                                        value:<?php echo $outgoingPercentage;?>,   
                                        vertical: false
                                     });
                                  
									slider23.linkTo('outgoingPercentage');
									
                                    
                                    slider23.init();
                                </script>
                                </div>
                            </div>
                         </div>
                    <div class="x-clear"></div>
                </div>
                
                <div class="expense-budget-entry">
                    <div class="expense-budget-entry-body">
                            <div class="overall-editor" style="margin-bottom: 0px;">
                                <div class="step">
                                    <div class="num"> 2</div>
          
                                    <h4 class="label">How many days will you wait, on average, before making outgoing payments?</h4>
                                    <div class="step-inner"><p>It is a good idea to pay your bills on a regular schedule, 
                                    rather than immediately after receiving them. Your suppliers will typically provide 
                                    15, 30, or more days of leeway before payment is due. The longer you can keep the 
                                    cash, the better for your working balance.</p>
                                     
                                     <div class="outgoing_payment">
                                         <input type="text" id="collectOutgoingPayment" name="collectOutgoingPayment" 
                                                                value="<?php echo $collectOutgoingPayment;?>" />	
                                            <span class="percent">days</span>
                                         		<script>
                                                var slider23 = new dhtmlxSlider(null, {
                                                    skin: "ball", //dhx_skyblue
                                                    min: 0,
                                                    max: 180,
                                                    step: 30,
                                                    size: 400,
                                                    value:<?php echo $collectOutgoingPayment;?>,   
                                                    vertical: false
                                                 });
                                              
                                                slider23.linkTo('collectOutgoingPayment');
                                                
                                                slider23.init();
                                            </script>
                                		</div>
                                   	</div>
                                </div>
                                <div class="x-clear"></div>
                            </div>
                        <div class="x-clear"></div>
            			<div class="step">
                          
                        <a class="done-editing button button-primary button-submit" href="Javascript:void(0);">
                            <span class="button-cap"><span>
                                <button name="update_outgoing_payment" class="update_outgoing_payment" type="submit">Update Outgoing</button>
                            </span></span>
                        </a>
               
                        </div>
                        <div class="x-clear"></div>
                    </div>
                    
                </div>
                <script type="text/javascript">
                    bpo.widgetPage.personnel.initEditor();
                </script>
            </div>
        
        </div>
    </form>
        </div>
    </div>
</div>