<?php 
ob_start();	
$pageTitle = "Cash Flow Projections";
include_once("../../Base.php"); 
include_once(TOP2);

		$pageContent = "";
		$currentPageData = new page_lib();
		
		
		$pageId = $currentPageData->getPageId();
		$getAllPageDetails	 = $currentPageData->pageContent($pageId);
 		
		$getPageTitle =  $getAllPageDetails[0]['pagetitle'];
		
		$getPageTitle =  "About The ".$getPageTitle;
            
        $getPageContent =  $getAllPageDetails[0]['page_content']; 
		
		if(isset($_POST['update_page_content'])) 
		{
			if($currentPageData->updatePageContent($pageId))
			{
				//$currentPageData->allmsgs[] = "Update Successful";
				//	$currentPageData->color = "blue";
				$global_func->Redirect($_SERVER['HTTP_ORIGIN']."/".$_SERVER['PHP_SELF']);
				//print_r($_SERVER);
			}
 		}
	
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
		
			$cashFlow = new cashFlowProjection_lib();
			$getPayments = $cashFlow->Payments($businessPlanId);
		}
	
	
	?>
              
       <section class="clearfix" id="content-container">	
    		<div class="clearfix" id="main-wrap" >
        
          <?php if(isset($_GET['cashflow']) == "payments") 
				{?><div id="main-wrap">
          				<div class="dim-action-active-page" id="content">
							<div id="widgets-container" class="chapter">
                    			<?php include_once('cash_flow_projection/payments.php');
				}
				else
				{ 
					include_once(LEFTPANEL);?>
					<div class="page_content_right sub-content">
          				<div class="dim-action-active-page" id="content">
							<div id="widgets-container" class="chapter">
								<?php	include_once('cash_flow_projection/cashflow_intro_block.php');
				
					
				}
				if(isset($_GET['cashflow']) == "payments") 
				{ }
				else{?>
					<!-- Footer -->
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
					 <!-- End Footer -->   
					 
                     
					 <?php } ?>
                                 
                         <div class="x-clear"></div>
                       </div><!-- End #widgets-container--> 
                       
                       </div> <!-- End Box -->
                    <span class="clear"></span>
        	</div><!-- END of page_content_right-->
	</div><!-- END #main-wrap -->
  
</section><!-- END content-container -->
<?php include_once(BOTTOM2);?>