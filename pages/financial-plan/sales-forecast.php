<?php 
ob_start();	
$pageTitle = "Sales Forecast";
include_once("../../Base.php"); 
include_once(TOP2);

		$pageContent = "";
		$currentPageData = new page_lib();
		
		
		$pageId = $currentPageData->getPageId();
		$getAllPageDetails	 = $currentPageData->pageContent($pageId);
 		
		$pageUrl = $getAllPageDetails[0]['pageurl'];
		$getPageTitle =  $getAllPageDetails[0]['pagetitle'];
		
		$getPageTitle =  $getPageTitle;
            
        $getPageContent =  $getAllPageDetails[0]['page_content']; 
		
		
		if(isset($_POST['update_page_content'])) 
		{
			if($currentPageData->updatePageContent($pageId))
			{
				
				$global_func->Redirect($_SERVER['HTTP_ORIGIN']."/".$_SERVER['PHP_SELF']);
				
			}
 		}
	
	?>
    
   <section class="clearfix" id="content-container">		
    		<div class="clearfix" id="main-wrap" >
        
          <?php if(isset($_GET['table']) and ($_GET['table']) == "forecast")
				{?>
                	
						<?php include_once(LEFTPANEL);?>
                        <div class="page_content_right sub-content">
          				<div class="dim-action-active-page" id="content">
							<div id="widgets-container" class="chapter">
					
                  <?php  
				include_once('sales-forecast/new_product.php');
				}	
				else if(isset($_GET['product']) and ($_GET['product']) == "sales")
				{?><div id="main-wrap">
          				<div class="dim-action-active-page" id="content">
							<div id="widgets-container" class="chapter">
                   <?php  include_once('sales-forecast/product_sales.php');        
                	
				}	
				else
				{ ?>
					
						<?php include_once(LEFTPANEL);?>
					 <div class="page_content_right sub-content">
          				<div class="dim-action-active-page" id="content">
							<div id="widgets-container" class="chapter">
                            
 	         
             
					<?php 
					include_once('sales-forecast/sales_forecast_intro.php');
					
					
				}?>
      				 <!-- Footer -->
                             <?php if(isset($_GET['product']) and ($_GET['product']) == "sales") { } else{?>
                             <div id="plan-footer">
                                <div class="footer-functions">
                                    <a class="edit-chapter" href="<?php $nextUrl ?>">Change what's in this chapter</a>
                                </div>
                                 <span class="button_continue">
                                    <a href="<?php echo $nextUrl; ?>">
                                        <img src="shortcodes_files/images/continue.png" width="110" height="37" border="0" align="right"> 
                                        <div class="x-clear"></div>
                                     </a>
                            	</span>
                             </div>  
                             <?php } ?>
                             
                             <!-- End Footer -->       
                         <div class="x-clear"></div>
                       </div><!-- End #widgets-container--> 
                       
                       </div> <!-- End Box -->
                    <span class="clear"></span>
        	</div><!-- END of page_content_right-->
	</div><!-- END main-wrap -->
  
</section><!-- END content-container -->
<?php include_once(BOTTOM2);?>