<?php
	
	$sales = new sales_forecast_lib();
	if(isset($_GET['sfId']))
	{
		$whereid = "sales_forecast.sf_id = ".(int)$_GET['sfId'];
		
		$allSalesDetails = $sales->getAllSales($whereid, "", "");
		
		/*----------------------------------------------
			IF update  product sale button was pressed
		------------------------------------------------*/
		if(isset($_POST['update_product_sale']))
		{
			$saleForecastId = $_GET['sfId'];
			
			if($sales->_updateTables($saleForecastId))
			{
				$currentPageUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?product=sales&sfId=$saleForecastId";
				$global_func->redirect($currentPageUrl);	
			}
		}
		/*----------------------------------------------
			Else iF update  Price  button was pressed
		------------------------------------------------*/
		else if(isset($_POST['update_price']))
		{
			$saleForecastId = $_GET['sfId'];
			$postedPrice = htmlentities(addslashes($_POST['price']),ENT_COMPAT, "UTF-8");
			$updateQuery = "price = '$postedPrice'";
			
			if($sales->_update12MonthData($saleForecastId, $updateQuery))
			{
				$currentPageUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?product=sales&sfId=$saleForecastId";
				$global_func->redirect($currentPageUrl);
			}
		}
		/*----------------------------------------------
			Else iF update Cost button was pressed
		------------------------------------------------*/
		else if(isset($_POST['update_cost']))
		{
			$saleForecastId = $_GET['sfId'];
			$postedCost = htmlentities(addslashes($_POST['cost']),ENT_COMPAT, "UTF-8");
			$updateQuery = "cost = '$postedCost'";
			
			if($sales->_update12MonthData($saleForecastId, $updateQuery))
			{
				$currentPageUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?product=sales&sfId=$saleForecastId";
				$global_func->redirect($currentPageUrl);
			}
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
                <h2><a href="<?php echo $_SERVER['PHP_SELF'];?>" class="backtoplan">Back to Outline</a></h2>
            </div>
            
             <div class="tableBuilder">
            <ul class="nav">
                    <li>
                        <a href="#expenses" class="active">
                            <span class="num">
    							1</span>
                            <span class="label">Units</span>
                            <span class="clear"></span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#burden">
                            <span class="num">
   							 2</span>
                            <span class="label">Prices</span>
                            <span class="clear"></span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#femi">
                            <span class="num">
   							 3</span>
                            <span class="label">Costs</span>
                            <span class="clear"></span>
                        </a>
                    </li>
            </ul>
            <div class="x-clear"></div>
            
            <div class="pages">
                <div class="page">
                	<div class="page-body">
                    	<div class="intro-block expanded">
                                <div class="widget-content">
                                    <h3>How much of this product will you sell?</h3>
                                    <p>How many "units" of this product or service will you sell? For a product business, a unit could be a shirt or a computer. 
                                    For a service business, it could be an hour of consulting time, or a single session. You decide what makes sense for your business.</p>
                                    <p>Click and type in each monthly cell to enter the number of units you will sell in the first year. Don't forget to enter annual projections 
                                    for the following years, down below.</p>
                                   
                                </div>
                            </div> <!--end .intro-block-->
               			    <div class="line-item">
                                    <div class="header">
                                        <h3>Product sales</h3>
                                    </div>
                                    <div class="content">
                                        <div class="step single-step">
                                            <div class="num">1</div>
                                            <h4 class="label">How much of this product will you sell?</h4>
                                      <div class="step-inner">
                                        <form class="product_sales_form" method="post">
                                            <div class="financial-table period-month financial-year-editor">
                                               
                                                    <div class="head">
                                                        <div class="row">
                                                               
																 <?php
                                                                    $twelveMonthsData = $sales->twelveMonths("", "");
                                                                    for($e_month = 0; $e_month < count($twelveMonthsData); $e_month++ )
                                                                    {
																		$years = substr($twelveMonthsData[$e_month], -2); 
																		$months = substr($twelveMonthsData[$e_month], 0, 3);
                                                                    ?>
                                                                        <div class="column column-month">
                                                                            <span>&nbsp;<?php echo $months." '".$years; ?></span>
                                                                        </div>
                                                                   <?php
                                                                    }
                                                                    ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="body">
                                                        <div class="row values">
                                                           
                                                            <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_01" value="<?php  echo $allSalesDetails[0]['month_01']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                            <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_02" value="<?php  echo $allSalesDetails[0]['month_02']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_03" value="<?php  echo $allSalesDetails[0]['month_03']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_04" value="<?php  echo $allSalesDetails[0]['month_04']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_05" value="<?php  echo $allSalesDetails[0]['month_05']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_06" value="<?php  echo $allSalesDetails[0]['month_06']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_07" value="<?php  echo $allSalesDetails[0]['month_07']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_08" value="<?php  echo $allSalesDetails[0]['month_08']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_09" value="<?php  echo $allSalesDetails[0]['month_09']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_10" value="<?php  echo $allSalesDetails[0]['month_10']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_11" value="<?php  echo $allSalesDetails[0]['month_11']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="month_12" value="<?php  echo $allSalesDetails[0]['month_12']; ?>"
                                                                 class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                             
                                            <div class="financial-table period-year financial-year-editor">
                                                <div>
                                                    <div class="head">
                                                        <div class="row">
														   <?php
                                                                $yrsOfFinancialForecast = $sales->financialYear();
                                                                for($e_yr = 0; $e_yr < count($yrsOfFinancialForecast); $e_yr++ )
                                                                 {
                                                                ?>	
                                                                    <div class="column column-year">
                                                                        <div class="td">FY<?php echo $yrsOfFinancialForecast[$e_yr]; ?></div>
                                                                    </div>
                                                             <?php } ?>   
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="body">
                                                        <div class="row vales">
                                                            <div class="column column-year column-total NUMBER SUM" style="float: left;" rel="0">
                                                                <div class="td">
                                                                    <p class="display-only"><?php  echo $allSalesDetails[0]['financial_status'][0]['total_per_yr']; ?></p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="column column-year" rel="1" id="ext-gen29">
                                                                <div class="td">
                                                                    <input type="text" name="totalForYr2" value="<?php  echo $allSalesDetails[0]['financial_status'][1]['total_per_yr']; ?>" 
                                                                    	class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14" >
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="column column-year" rel="2" id="ext-gen30">
                                                                <div class="td">
                                                                    <input type="text" name="totalForYr3" value="<?php  echo $allSalesDetails[0]['financial_status'][2]['total_per_yr']; ?>" 
                                                                    	class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                        </div>
                                                         <span class="clear"></span>
                                                         <p>&nbsp;</p>
                                                       
                                                               <button type="submit" name="update_product_sale" style="width:105px;">Update</button>
                                                         
                                                    </div>
                                                </div>
                                           </div>
                                         </form>
                                     </div><!--end of step-inner-->
                                    </div>
                                    <span class="clear"></span>
                                </div><!---end of .content-->
                                    
                                    <span class="clear"></span>
                                </div><!--end .line-item-->
                                
                                <div class="page-footer">
                               
                                <div class="right">
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>"  class="button button-gray"><span class="button-cap"><span>I'm Done</span></span></a>
                                    <span class="button button-primary continue"><span class="button-cap"><span>Continue</span></span></span>
                                </div>
                            </div>
                     </div><!--end .page-body-->
           	   	</div><!--end of page-->
                <div class="page">
                    <div id="expenseBudget:other-spending">
                            <div class="page-body">
                                <div class="intro-block">
                                    <div class="widget-content" style="padding-bottom: 20px;">
                                        <h3>What will you charge for each unit?</h3>
                                        <p>You'll need to figure out the average selling price for each unit of this product or service. Do you bill at <?php echo $_SESSION['bpcurrency']?>150 per hour? 
                                        Sell shirts that average <?php echo $_SESSION['bpcurrency']?>25 or <?php echo $_SESSION['bpcurrency']?>65? Don't worry about getting the exact price 
                                        right, we're doing planning 
                                        and it's about summarizing and making generalizations. But, if there is really no common-sense average price for this category, you might want to
                                         rethink how you grouped your products or services together.</p>
                                         
                                        <p>You can use the same price-per-unit for each month, or vary them seasonally if you plan to offer 
                                        special sales. Use the link in the lower left corner, if necessary, to switch between having one constant price or changing your price from period to period</p>
                                        <span class="clear"></span>
                                    </div>
                                </div>
                            
							<div class="line-item">
                                        <div class="header">
                                            <h3>Unit Prices</h3>
                                        </div>
                                        <div class="content">
                                            <div class="step single-step">
                                                <div class="num">1</div>
                                                <h4 class="label">What will you charge for each unit?</h4>
                                                <div class="step-inner">
                                                	  <form method="post" action="" class="product_sales_form">	
                                                        <div id="unitPrices" class="overall-editor settings-container">
                                                            <span class="currency"> <?php echo $_SESSION['bpcurrency']?></span>
                                                                <input  type="text" name="price" value="<?php  echo $allSalesDetails[0]['price']; ?>" 
                                                                	class="currency active-input numeric currency nonnegative" maxlength="14" >
                                                                 <button type="submit" name="update_price" style="width:105px;">Update Price</button>
                                                            <div class="x-clear"></div>
                                                        </div>
                                                       
                                                   	</form>
                                                    <span class="clear"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="clear"></span>
                                    </div>
                          
</div>
                            <div class="page-footer">
                                <div class="left">
                                </div>
                                <div class="right"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="button button-gray"><span class="button-cap"><span>I'm Done</span></span></a>
                                    <span class="button button-primary continue"><span class="button-cap"><span>Continue</span></span></span>
                                </div>
                            </div><span class="clear"></span><br/>
                            </div>
                          </div><!--end of page-->
            
             <div class="page">
             	<div class="page taxes" style="display: block; "><div id="expenseBudget:taxes">
                            <div class="page-body"><div id="expenseBudget:j_id654" class="intro-block expanded">
                  <div class="widget-content">
                     <h3>How much will it cost you just to provide each unit?</h3>
                     <p>If you buy shirts for one price and sell them for another, this is easy: whatever you spend on buying a single shirt as inventory is your direct cost. However, 
                     if you add value to your products along the way (a restaurant doesn't just resell raw food, it chops and mixes and cooks it), you may want to take that into account
                     . Basically, your direct costs are anything that gets completely used up or goes away when you sell your product or service. This also means that the more 
                     you sell, the higher your related costs are.</p>
                     <p>Even service businesses may have direct costs. For example, a law firm could track its lawyers' billable hour salaries
                      as direct costs; they vary in direct relation to how much the same lawyer bills the clients.</p>
                 </div></div>
                 
            			
                        
                        <div class="line-item">
                                        <div class="header">
                                            <h3>Direct costs</h3>
                                        </div>
                                        <div class="content">
                                            <div class="step single-step">
                                                <div class="num">1</div>
                                                <h4 class="label">How much will it cost you just to provide each unit?</h4>
                                                <div class="step-inner">
                                                	  <form method="post" action="" class="product_sales_form">	
                                                        <div id="unitPrices" class="overall-editor settings-container">
                                                            <span class="currency"> <?php echo $_SESSION['bpcurrency']?></span>
                                                                <input  type="text" name="cost" value="<?php  echo $allSalesDetails[0]['cost']; ?>" 
                                                                	class="currency active-input numeric currency nonnegative" maxlength="14" >
                                                                 <button type="submit" name="update_cost" style="width:105px;">Update Cost</button>
                                                            <div class="x-clear"></div>
                                                        </div>
                                                       
                                                   	</form>
                                                    <span class="clear"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="clear"></span>
                                    </div>
                        
                        
                             
                            </div>
                            <div class="page-footer">
                                <div class="left">

                                </div>
                                <div class="right"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="button button-primary continue">
                                        <span class="button-cap">
                                            <span>I'm Done</span>
                                        </span></a>
                                </div>
                            </div><span class="clear"></span><br/>
                         </div>
                    </div>
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
  	