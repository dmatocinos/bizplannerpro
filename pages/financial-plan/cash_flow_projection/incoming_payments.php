<?php
	if($getPayments)
	{

		$incomingPercentage =  $getPayments[0]['percentage_sale']; 
		$collectIncomingPayment =  $getPayments[0]['days_collect_payments']; 
	}
	else
	{
		$incomingPercentage =  0; 
		$collectIncomingPayment =  0; 
	}
	/*----------------------------------------------------------------------------------------------------
		EDIT / DELETE EMPLOYEE DETAILS
	*-----------------------------------------------------------------------------------------------------*/
	
	// if update button
	if(isset($_POST['update_incoming_payment']))
	{
		
		$incomingPercentage = $_POST['incomingPercentage'];
		$collectIncomingPayment = $_POST['collectIncomingPayment'];
	
		
		if($cashFlow->updateIncomingPayments($businessPlanId, $incomingPercentage, $collectIncomingPayment))
		{
			$cashFlow->global_func->redirect($_SERVER['PHP_SELF']."?cashflow=payments");	
		}
		
		else
		{
			$cashFlow->allmsgs = "Incoming Payments could not be updated. Please try again.";
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
	.incoming_payment{
		background-color:#c3c3c3;
		min-height:70px;
		width: 696px;
		border:5px dashed #e6e6e6;	
	}
	input#incomingPercentage, input#collectIncomingPayment{
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
	input#collectIncomingPayment{
			
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
                    <h3>Incoming payments</h3>
                   
                </div>
                <div class="expense-budget-entryMethod">
                    <div class="step expense-name">
                        <div class="num"> 1</div>
           
                        
                        <h4 class="label">What percentage of your sales will be on credit?</h4>
                            
                           <div class="step-inner">
                        	<p>Select the estimated portion of your sales revenue that will be invoiced for 
                            later payment, rather than paid at the time of the purchase.</p>
                           
                             						
                             <div class="incoming_payment">
                             <input type="text" id="incomingPercentage" name="incomingPercentage" 
                             						value="<?php echo $incomingPercentage;?>" />	<span class="percent">%</span>
							 <script>
                                    var slider23 = new dhtmlxSlider(null, {
                                        skin: "ball", //dhx_skyblue
                                        min: 0,
                                        max: 100,
                                        step: 5,
                                        size: 400,
                                        value:<?php echo $incomingPercentage;?>,   
                                        vertical: false
                                     });
                                  
									slider23.linkTo('incomingPercentage');
									
                                    
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
          
                                    <h4 class="label">How many days will it take, on average, to collect incoming payments?</h4>
                                    <div class="step-inner"><p>Select the typical number of days between when you make a 
                                    credit sale and when the payment arrives. Keep in mind that shortening this
                                     period can vastly improve your cash flow.</p>
                                     
                                     
                                     
                                     <div class="incoming_payment">
                                         <input type="text" id="collectIncomingPayment" name="collectIncomingPayment" 
                                                                value="<?php echo $collectIncomingPayment;?>" />	
                                            <span class="percent">days</span>
                                         <script>
                                                var slider23 = new dhtmlxSlider(null, {
                                                    skin: "ball", //dhx_skyblue
                                                    min: 0,
                                                    max: 180,
                                                    step: 30,
                                                    size: 400,
                                                    value:<?php echo $collectIncomingPayment;?>,   
                                                    vertical: false
                                                 });
                                              
                                                slider23.linkTo('collectIncomingPayment');
                                                
                                                
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
                                <button name="update_incoming_payment" class="update_incoming_payment" type="submit">Update Incoming</button>
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