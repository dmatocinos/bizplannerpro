<?php 
ob_start();	
include_once("../../Base.php"); 
include_once(TOP2);

		$pageContent = "";
		$currentPageData = new page_lib();
		
		
		$pageId = $currentPageData->getPageId();
		$getAllPageDetails	 = $currentPageData->pageContent($pageId);
 		
		$getPageTitle =  $getAllPageDetails[0]['pagetitle'];
        
		$getPageTitle =  "About The ".$getPageTitle;
        
		$getPageContent =  $getAllPageDetails[0]['page_content']; 
		
	
		
		$getSectionData = $currentPageData->sectionData($pageId);
		
		
		if(isset($_POST['update_page_content'])) 
		{
			if($currentPageData->updatePageContent($pageId))
			{
				//$currentPageData->allmsgs[] = "Update Successful";
				//	$currentPageData->color = "blue";
				//$global_func->Redirect($_SERVER['HTTP_ORIGIN']."/".$_SERVER['PHP_SELF']);
				$slash = $_SERVER['HTTP_ORIGIN'] == ""? "": "/";				
				$global_func->Redirect($_SERVER['HTTP_ORIGIN']. $slash .$_SERVER['PHP_SELF']);
			}
 		}
		else if(isset($_POST['update_section_content'])) 
		{
			
			echo $sectionId = $_POST['sectionId'];
			if($currentPageData->updateSectionContent($sectionId, $pageId))
			{
				//$global_func->Redirect($_SERVER['HTTP_ORIGIN']."/".$_SERVER['PHP_SELF']);
				$slash = $_SERVER['HTTP_ORIGIN'] == ""? "": "/";				
				$global_func->Redirect($_SERVER['HTTP_ORIGIN']. $slash .$_SERVER['PHP_SELF']);
			}
			
 		}
	?>
              
         <section class="clearfix" id="content-container">	
    		<div class="clearfix" id="main-wrap" >
                <?php include_once(LEFTPANEL);?>
            
            
             <div class="page_content_right sub-content">
          		<div class="dim-action-active-page" id="content">
						
                      <div id="widgets-container" class="chapter">
                            <h1>
                                <span class="edit">
                                   <!-- <span class="outline-num">1.</span>-->
                                    <span class="title" id="chapterName"><?php echo $pageTitle; ?></span>
                                </span>
                            </h1>
                        	<div class="x-clear"></div>
             			<!-- Box 1-->
               		<style>
						table#page_content_tbl{
							width:100%;
							}
							
					</style>
                   
                             
                             
                             
                             
                             
                             
                             
                             
                             
                             
                             
                             
                             
                             
                             
                             
                             
                           
						  
                            
                            
                            
                            
                            
                            
                     
