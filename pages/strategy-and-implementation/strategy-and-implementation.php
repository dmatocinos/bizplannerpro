<?php 
ob_start();	
$pageTitle = "Strategy and Implementation";
include_once("../../Base.php"); 
include_once(TOP2);

?>
        
       
        <section class="clearfix" id="content-container">	
    		<div class="clearfix" id="main-wrap" >
                <?php include_once(LEFTPANEL);?>
            
             <div class="page_content_right sub-content">
          
		        <!-- Box -->         
        
      <div class="dim-action-active-page" id="content">
						
                      <div id="widgets-container" class="chapter">
                            <h1>
                                <span class="edit">
                                   <!-- <span class="outline-num">1.</span>-->
                                    <span class="title" id="chapterName"><?php echo $pageTitle; ?></span>
                                </span>
                            </h1>
                         <p class="intro-paragraph">
                        <strong>Here's what we will cover in this chapter.</strong> Click any file heading to get started. 
                        Certain files may not be relevant to your company, complete only those you feel are necessary to your business plan.
                		</p>
                
                
                
            <div class="x-clear"></div>
            
            <!--<div class="img_bg"><img src="shortcodes_files/images/stock-photo-13821294-notebook.jpg"  alt="bg_img"  class="bg_phot"/></div>-->
             <!-- Box 0-->
                        
                         <?php 
						 	foreach($childrenMenu as $eachBlock)
							{
						 ?>
                         <div class="section-widge" >
                            <a href="<?php echo  $parentPageUrl.'/'.$eachBlock['pageurl']; ?>" class="block" >
                                <div class="section-wrapper">
                                
                                    <div class="block_title">
                                    <!--<img src="shortcodes_files/images/maroverv.gif" >-->
                                        <div class="section-title">
                                           <h3>
                                                <span class="title">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $eachBlock['pagetitle'];?></span>
                                            </h3>
                                                <p>
                                                   &nbsp;&nbsp;&nbsp;&nbsp; <!--  Not started yet  -->
                                                </p>
                                        </div>
                                         <div class="x-clear"></div>
                                    </div><!-- end .block_title -->
                                        
                                       
                                    <div class="items">
                                        <p class="in-this-section">In this section:</p>
                                        <div class="item-list">
                                                <span class="item">
                                                   <span class="item_icon">
                                                    	<img src="shortcodes_files/images/iiic.png" width="16" height="22" border="0"></span>	                                            <span> 
                                                    	<br/>  &nbsp; &nbsp;  &nbsp; &nbsp;  
                                                        <img src="shortcodes_files/images/eee.PNG"> <?php echo $eachBlock['pagetitle'];?>&nbsp;
                                                        
                                                    </span>  
                                                </span>
                                            <div class="x-clear"></div>
                                        </div>
                                        <div class="x-clear"></div>
                                   </div><!----end of .items -->
                                    <div class="x-clear"></div>
                                  </div><!--end of .section-wrapper-->
                              
                           </a>
                           <div class="x-clear"></div>
                        </div> 
                  
                        <?php } ?>
                        
                            <!-- Footer -->
                             <div id="plan-footer">
                                <div class="footer-functions">
                                    <a class="edit-chapter" href="<?php $changeChapterUrl ?>">Change what's in this chapter</a>
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
                    <span class="clear"></span>
        	</div><!-- END of page_content_right-->
	</div><!-- END main-wrap -->
  
</section><!-- END content-container -->
<?php include_once(BOTTOM2);?>
