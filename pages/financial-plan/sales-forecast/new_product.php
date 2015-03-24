  <?php
  
  	// Define variables and instantiate Class
	$dummy_new_sale_name = "Add Product Or Service";
	$new_sale_name = $dummy_new_sale_name;
	$sales = new sales_forecast_lib();
	
	
	/*----------------------------------------------------------------------*
		If Create Button was pressed
	**---------------------------------------------------------------------*/
  	if(isset($_POST['create_sale']))
	{
		$form_validation ="validate_new_sale_form"; require("../form_validate.php");
		
		if (empty($outputMsg))
		{
			if($sales->createNewSale($new_sale_name))
			{
				$currentPageUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?table=forecast";
				$global_func->redirect($currentPageUrl);
			}
		}
		else 
		{
			$sales->allmsgs = $outputMsg;
			$sales->color = $color;
		}
	}
	/*----------------------------------------------------------------------*
		If Update Button was pressed
	**---------------------------------------------------------------------*/
	else if(isset($_POST['update_forecast']))
	{
		$saleForecastId = $_POST['sales_forecast_id'];
		$saleForecastName = $_POST['sales_forecast_name'];
		
		
		if($sales->_updateSalesForecastTable($saleForecastId, $saleForecastName))
		{
			$currentPageUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?table=forecast";
			$global_func->redirect($currentPageUrl);
		}	
	}
	
	/*----------------------------------------------------------------------*
		If Delete Button was pressed
	**---------------------------------------------------------------------*/
	else if(isset($_POST['delete_forecast']))
	{
		$saleForecastId = $_POST['sales_forecast_id'];
		if($sales->deleteSaleForecast($saleForecastId))
		{
			$currentPageUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?table=forecast";
			$global_func->redirect($currentPageUrl);
		}
	}
	
	/*----------------------------------------------------------------------*
		If add forecast Button was pressed
	**---------------------------------------------------------------------*/
	else if(isset($_POST['view_forecast']))
	{
		$saleForecastId = $_POST['sales_forecast_id'];
		$currentPageUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?product=sales&sfId=".$saleForecastId;
		$global_func->redirect($currentPageUrl);
		
	}
	
	// Get all sales forecast Details
	$allSalesDetails = $sales->getAllSales("", "", "");
  

	
  
  ?>
   <p> <?php  $sales->DisplayAllMsgs('','');  ?></p>
         <div class="x-clear"></div>
        
        	<div class="sales_forecast_table">
                    <div class="header" unselectable="on">
                        <h2>
                             <span id="widgetTitleSpan_4ae8d5fe-5893-44c7-8c63-7828973f51cd" class="title">Sales Forecast Table</span>
                         </h2>
                     </div>
                
                                
                                <div class="edit">
                                    <div class="widget-body">
                                   <p><strong>What do you sell?</strong><br />Break down what you sell into groups of products or services.
                                     You might group offerings together based on price, how you provide them, 
                                    or which kind of customer buys them. For example, a fitness center might separate sales of group 
                                    memberships, individual memberships, and personal training services. 
                                    A shoe store might list sneakers, dress shoes, children's shoes, and waterproof sealer.</p>
                                    <p>Keep this list short. Trying to list dozens of individual 
                                    products will make your forecast difficult to predict, maintain, and understand. 
                                    Roll up your offerings into half a dozen categories or fewer.</p>
                    
                    
                                <div class="line-item">
                                  <form class="forecast" method="post">
                                        
                                        <input name="new_sale_name" value="<?php echo $new_sale_name;?>"  type="text"
                                                    onfocus="if(this.value=='<?php echo $dummy_new_sale_name;?>') this.value='';" 
                									onblur="if(this.value=='') this.value='<?php echo $dummy_new_sale_name;?>';"
                                                     maxlength="255"    />
                                        
                                        
                                        
                                        <span class="icon-tablebuilder ">
                                             <button type="submit" class="new_forecast" name="create_sale" style="width:100px;"> Create Sale</button>
                                        </span>
                                    </form>
                                </div>
                                <p>&nbsp;</p>
                           	<?php 	if($allSalesDetails) 
									{	
										foreach($allSalesDetails as $salesDetails)
										{
									?>
                                        <div class="line-item">
                                            <form class="forecast" method="post">
                                                
                                                <input type="hidden" value="<?php echo $salesDetails['sf_id']?>" name="sales_forecast_id">
                                                
                                                <button type="submit" class="delete_forecast_button" name="delete_forecast" 
                                                		onclick="if(confirm('Are you sure you want to remove this Product / Service ?')){return true }else{ return false}" ></button>
                                                
                                                <input type="text" value="<?php echo $salesDetails['sales_forecast_name'];?>" name="sales_forecast_name" maxlength="255">
                                                
                                                 <span class="icon-tablebuilder first_button ">
                                                        <button type="submit" class="forecast_button" name="update_forecast"> Update</button>
                                                </span>
                                                
                                                <span class="icon-tablebuilder ">
                                                    <span class="create_forecast"> 
                                                        <button type="submit" class="add_forecast" name="view_forecast"> Forecast</button> 
                                                    </span>    
                                                </span>
                                            </form>
                                        </div><!--end .line-item-->
                                        <div class="x-clear"></div>
                                <?php 	}
									} ?>
                                
                                
                                    </div> 
                                   
                                   <div class="widget-ainer">
                                       
                                        <a  href="<?php echo $parentPageUrl.'/'.$pageUrl; ?>" style="margin-top: 10px; float: right;" class="button button-primary x-unselectable continue">
                                            <span class="button-cap"><span>I'm Done</span></span>
                                        </a>
                                        	<div class="x-clear"></div>
                                    	</div>
                                     </div><!--end edit-->
                                   <div class="x-clear"></div>
                              </div><!--end .sales_forecast_table-->