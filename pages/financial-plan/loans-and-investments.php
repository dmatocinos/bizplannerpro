<?php 
ob_start();	

$pageTitle = "Loan and Investments";

include_once("../../Base.php"); 
include_once(TOP2);

		$pageContent = "";
		$currentPageData = new page_lib();
		
		
		$pageId = $currentPageData->getPageId();
		$getAllPageDetails	 = $currentPageData->pageContent($pageId);
 		
		$getPageTitle =  $getAllPageDetails[0]['pagetitle'];
		
		$getPageTitle =  "Source of Fund";
            
        $getPageContent =  $getAllPageDetails[0]['page_content']; 
		
		$getSectionData = $currentPageData->sectionData($pageId);
		
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
		else if(isset($_POST['update_section_content'])) 
		{
			
			echo $sectionId = $_POST['sectionId'];
			if($currentPageData->updateSectionContent($sectionId, $pageId))
			{
				//$global_func->Redirect($_SERVER['HTTP_ORIGIN']."/".$_SERVER['PHP_SELF']);
				//echo $_SERVER['HTTP_ORIGIN']."/".$_SERVER['PHP_SELF']; die();
				$slash = $_SERVER['HTTP_ORIGIN'] == ""? "": "/";				
				$global_func->Redirect($_SERVER['HTTP_ORIGIN']. $slash .$_SERVER['PHP_SELF']);
			}
			
 		}
	
	?>
              
         <section class="clearfix" id="content-container">	
    		<div class="clearfix" id="main-wrap" >
        
          <?php if(isset($_GET['projection']) == "_loan_invest")
				{?>
                	<div id="main-wrap">
          				<div class="dim-action-active-page" id="content">
							<div id="widgets-container" class="chapter">
                    <?php        
					include_once('loans-and-investments/new_projection.php');
				}
				else
				{ ?>
                
                    <?php include_once(LEFTPANEL);?>
                    <div class="page_content_right sub-content">
                    <div class="dim-action-active-page" id="content">
                        <div id="widgets-container" class="chapter">
					<?php 
					// Calculation is in this file
					include_once('loans-and-investments/loan_investment_intro_block.php');
					
				}?>
                
                		  <?php if(isset($_GET['projection']) == "_loan_invest") { } else{?>
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
	</div><!-- END main-wrap -->
  
</section><!-- END content-container -->
<?php include_once(BOTTOM2);?>
