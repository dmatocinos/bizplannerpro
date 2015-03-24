<?php 
ob_start();	
include_once("../../Base.php"); 
include_once(TOP2);

		$pageContent = "";
		$currentPageData = new page_lib();
		
		
		$pageId = $currentPageData->getPageId();
		$getAllPageDetails	 = $currentPageData->pageContent($pageId);
 		
		$getPageTitle =  $getAllPageDetails[0]['pagetitle'];
            
        $getPageContent =  $getAllPageDetails[0]['page_content']; 
		
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
		
		
		//pageorder
		
	?>
              
         <section class="clearfix" id="content-container">	
    		<div class="clearfix" id="main-wrap" >
                <?php include_once(LEFTPANEL);
					//print_r($childrenMenu[1]['pageurl']);
				
						
					
					//if($childrenMenu[1]['pageurl'] == $getPageTitle)
					
				?>
            
            
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
               		
                      
                            <p> <?php  $currentPageData->DisplayAllMsgs('','');  ?></p>
                             <?php if( !empty($_GET['edit']) && !empty($_GET['pageid']) ){ ?>
                             
<?php // ------------------------------- ?>
<a class="intro-block-toggle expanded" href="javascript:void(0);" id="ext-gen13"><span>Hide Instructions</span></a>
<div id="introText" class="intro-block dim-action-intro-block" style="display: block; ">
	<span class="tip"></span>
	<div class="widget-content">
		<p> <?php echo $introText; ?></p>
		<span class="clear"></span>
	</div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php // ------------------------------- ?>
                                <form method="post" >
                                     <div class="rich_textarea">
                                            <textarea id="page_content" name="page_content" type="text" >
													<?php echo $getPageContent; ?></textarea>
                                      </div>
                                      <br />
                                      <button class="update_page" name="update_page_content" type="submit">Save and Continue</button>
                                  </form>
                       		 <?php }else{ ?>
                        			<?php if(!empty($getPageContent)){?>
                                        <div class="edit_section">
                                            <div class="widget_content">
                                                <h3><?php echo $getPageTitle; ?></h3>
                                                <div class="click-to-edit" >
                                              		<div class="tuck">
                                                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=page&pageid=<?php echo $pageId;?>">
                                                            <div class="flag">
                                                            <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                                                            </div>
                                                        </a>
                                                    </div>
                                               </div>
                                                <div class="clearboth"></div>
                                                
                                                <br/>
                                                <?php echo html_entity_decode( $getPageContent); ?>
                                                
                                            </div>
                                            
                                        </div><!--end .edit_section-->
                                    <?php }else{?>
                                    <div class="section">
                                         <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=page&pageid=<?php echo $pageId;?>">
                                            <div class="widget text clean-slate">​
                                                    <h3><?php echo $getPageTitle;?></h3>
                                                    <p>Get started on writing this item</p>
                                            </div>
                                        </a>
                                    </div>
                                    
                            <?php } ?>
                           
						  <?php } ?>
                         <!-- Footer -->
                             <div id="plan-footer">
                                <div class="footer-functions">
                                    <a class="edit-chapter" href="#">Change what's in this chapter</a>
                                </div>
                                 <span class="button_continue">
                                    <a href="<?php echo $nextUrl; ?>">
                                        <img src="shortcodes_files/images/continue.png" width="110" height="37" border="0" align="right"> 
                                        <div class="x-clear"></div>
                                     </a>
                            	</span>
                             </div>  
                             <!-- End Footer -->       
                         <div class="x-clear"></div>
                       </div><!-- End #widgets-container--> 
                       
                       
                       </div> <!-- End Box -->
            
        </div><!-- END of page_content_right-->
	</div><!-- END main-wrap -->
  
</section><!-- END content-container -->
<?php include_once(BOTTOM2);?>
