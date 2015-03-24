<?php 
ob_start();	
$pageTitle = "Financial Plan";
include_once("../../Base.php"); 
include_once(TOP2);

?>
        
       
        <section class="clearfix" id="content-container">	
    		<div class="clearfix" id="main-wrap" >
                <?php include_once(LEFTPANEL);?>
            	
            
             <div class="page_content_right sub-content">
          
		        <!-- Box -->         
        
      			<div class="dim-action-active-page" id="content" >
						
                      <div id="widgets-container" class="chapter">
                            <h1>
                                <span class="edit">
                                   <!-- <span class="outline-num">1.</span>-->
                                    <span class="title" id="chapterName"><?php echo $pageTitle; ?></span>
                                </span>
                            </h1>
                         <p class="intro-paragraph">
                        <strong>Here's what we will cover in this section.</strong> 
                        Click any file heading to get started. Certain files may not be relevant to your 
                        company, complete only those you feel are necessary to your business plan. 
                		</p>
            <div class="x-clear"></div>
            
           
             		<!-- Box 0-->
                        <div class="section-widge" >
                        
						<?php $box_no = 0;?>
                        	<a href="<?php echo  $parentPageUrl.'/'.$childrenMenu[$box_no]['pageurl']; ?>" class="block" >
                                <div class="section-wrapper">
                                
                                    <div class="block_title">
                                    <img src="shortcodes_files/images/Sale_forec.gif" >
                                        <div class="section-title">
                                           <h3>
                                                <span class="title"><?php echo $childrenMenu[$box_no]['pagetitle'];?></span>
                                            </h3>
                                                <p>
                                                    <!--  Not started yet  -->
                                        </div>
                                         <div class="x-clear"></div>
                                    </div><!-- end .block_title -->
                                        
                                       
                                    <div class="items">
                                        <p class="in-this-section">In this section:</p>
                                        <div class="item-list">
                                                <span class="item">
                                                	<span class="item_icon"><img src="shortcodes_files/images/iiic.png" width="16" height="22" border="0"></span>
                                                    <span class="item_list_icon"> 
                                                        <br/><img src="shortcodes_files/images/eee.PNG"> <?php echo $childrenMenu[$box_no]['pagetitle'];?> Table
                                                        <br/><br/><img src="shortcodes_files/images/eee.PNG"> Gross Margin by Year
                                                        <br/><br/> <img src="shortcodes_files/images/eee.PNG"> About <?php echo $childrenMenu[$box_no]['pagetitle'];?>     
                                                                                                      
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
                  
                        <!-- Box 1-->
                         <div class="section-widge" >
                          <?php $box_no = 1; ?>
                             <a href="<?php echo  $parentPageUrl.'/'.$childrenMenu[$box_no]['pageurl']; ?>" class="block" >
                                <div class="section-wrapper">
                                
                                    <div class="block_title">
                                    <img src="shortcodes_files/images/Pers_pan.gif" />
                                    
                                        <div class="section-title" >
                                           <h3>
                                                <span class="title"><?php echo $childrenMenu[$box_no]['pagetitle'];?></span>
                                            </h3>
                                                <p>
                                                    <!--  Not started yet  -->
                                                </p>
                                        </div>
                                         <div class="x-clear"></div>
                                    </div><!-- end .block_title -->
                                        
                                       	
                                    <div class="items">
                                        <p class="in-this-section">In this section:</p>
                                        <div class="item-list">
                                                <span class="item">
                                                   <span class="item_icon"><img src="shortcodes_files/images/iiic.png" width="16" height="22" border="0"></span>	
                                                      <span class="item_list_icon"> 
                                                        <br/>  
                                                        <img src="shortcodes_files/images/eee.PNG"> Personnel Plan
                                                        <br/><br/>
                                                        <img src="shortcodes_files/images/eee.PNG"> About <?php echo $childrenMenu[$box_no]['pagetitle'];?>
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
                        
                         <!-- Box 2-->
                         <div class="section-widge" >
                         <?php $box_no = 2; ?>
                             <a href="<?php echo  $parentPageUrl.'/'.$childrenMenu[$box_no]['pageurl']; ?>" class="block" >
                                <div class="section-wrapper">
                                
                                    <div class="block_title">
                                    <img src="shortcodes_files/images/Budget.gif" />
                                    
                                        <div class="section-title" >
                                           <h3>
                                                <span class="title"><?php echo $childrenMenu[$box_no]['pagetitle'];?></span>
                                            </h3>
                                                <p>
                                                    <!--  Not started yet  -->
                                                </p>
                                        </div>
                                         <div class="x-clear"></div>
                                    </div><!-- end .block_title -->
                                        
                                       
                                    <div class="items">
                                        <p class="in-this-section">In this section:</p>
                                        <div class="item-list">
                                                <span class="item">
                                                   <span class="item_icon">
                                                    	<img src="shortcodes_files/images/iiic.png" width="16" height="22" border="0"></span>	                                            
                                                       <span class="item_list_icon"> 
                                                            <br/>  
                                                            <img src="shortcodes_files/images/eee.PNG"> <?php echo $childrenMenu[$box_no]['pagetitle'];?> Table
                                                            <br/><br/>  
                                                            <img src="shortcodes_files/images/eee.PNG"> Expenses by the year
                                                            
                                                            <br/><br/>
                                                            <img src="shortcodes_files/images/eee.PNG"> About <?php echo $childrenMenu[$box_no]['pagetitle'];?>                                                    
    
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
                         
                         <!-- Box 3-->
                         <div class="section-widge" >
                         <?php $box_no = 3; ?>
                             <a href="<?php echo  $parentPageUrl.'/'.$childrenMenu[$box_no]['pageurl']; ?>" class="block" >
                                <div class="section-wrapper">
                                
                                    <div class="block_title">
                                    <img src="shortcodes_files/images/chart_line.png" />
                                    
                                        <div class="section-title" >
                                           <h3>
                                                <span class="title"><?php echo $childrenMenu[$box_no]['pagetitle'];?></span>
                                            </h3>
                                                <p>
                                                    <!--  Not started yet  -->
                                                </p>
                                        </div>
                                         <div class="x-clear"></div>
                                    </div><!-- end .block_title -->
                                        
                                       
                                    <div class="items">
                                        <p class="in-this-section">In this section:</p>
                                        <div class="item-list">
                                                <span class="item">
                                                   <span class="item_icon">
                                                    	<img src="shortcodes_files/images/iiic.png" width="16" height="22" border="0"></span>	                                            
                                                        <span class="item_list_icon"> 
                                                            <br/>  
                                                            <img src="shortcodes_files/images/eee.PNG"> <?php echo $childrenMenu[$box_no]['pagetitle'];?> Table
                                                            <br/><br/> 
                                                            <img src="shortcodes_files/images/eee.PNG"> Expenses by the year
                                                            
                                                            <br/><br/>
                                                            <img src="shortcodes_files/images/eee.PNG"> About <?php echo $childrenMenu[$box_no]['pagetitle'];?>                                                    

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
                        
                         <!-- Box 4-->
                         <div class="section-widge" >
                         <?php $box_no = 4; ?>
                             <a href="<?php echo  $parentPageUrl.'/'.$childrenMenu[$box_no]['pageurl']; ?>" class="block" >
                                <div class="section-wrapper">
                                
                                    <div class="block_title">
                                    <img src="shortcodes_files/images/bookmark.png" />
                                    
                                        <div class="section-title" >
                                           <h3>
                                                <span class="title"><?php echo $childrenMenu[$box_no]['pagetitle'];?></span>
                                            </h3>
                                                <p>
                                                    <!--  Not started yet  -->
                                                </p>
                                        </div>
                                         <div class="x-clear"></div>
                                    </div><!-- end .block_title -->
                                        
                                       
                                    <div class="items">
                                        <p class="in-this-section">In this section:</p>
                                        <div class="item-list">
                                            <span class="item">
                                               <span class="item_icon">
                                                    <img src="shortcodes_files/images/iiic.png" width="16" height="22" border="0"></span>	                                            
                                                    <span class="item_list_icon"> 
                                                        <br/>    
                                                        <img src="shortcodes_files/images/eee.PNG"> <?php echo $childrenMenu[$box_no]['pagetitle'];?> Table
                                                        <br/><br/>    
                                                        <img src="shortcodes_files/images/eee.PNG"> Expenses by the year
                                                        
                                                        <br/><br/>
                                                        <img src="shortcodes_files/images/eee.PNG"> About <?php echo $childrenMenu[$box_no]['pagetitle'];?>                                                    
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
                        
                        
                        
                         <!-- Box 4-->
                         <!--div class="section-widge" >
                         <?php $box_no = 5; ?>
                             <a href="<?php echo  $parentPageUrl.'/'.$childrenMenu[$box_no]['pageurl']; ?>" class="block" >
                                <div class="section-wrapper">
                                
                                    <div class="block_title">
                                    <img src="shortcodes_files/images/bookmark.png" />
                                    
                                        <div class="section-title" >
                                           <h3>
                                                <span class="title"><?php echo $childrenMenu[$box_no]['pagetitle'];?></span>
                                            </h3>
                                                <p>
                                                </p>
                                        </div>
                                         <div class="x-clear"></div>
                                    </div>
                                        
                                       
                                    <div class="items">
                                        <p class="in-this-section">In this section:</p>
                                        <div class="item-list">
                                            <span class="item">
                                               <span class="item_icon">
                                                    <img src="shortcodes_files/images/iiic.png" width="16" height="22" border="0"></span>	                                            
                                                    <span class="item_list_icon"> 
                                                        <br/>    
                                                        <img src="shortcodes_files/images/eee.PNG"> <?php echo $childrenMenu[$box_no]['pagetitle'];?> Table
                                                        <br/><br/>    
                                                        <img src="shortcodes_files/images/eee.PNG"> Expenses by the year
                                                        
                                                        <br/><br/>
                                                        <img src="shortcodes_files/images/eee.PNG"> About <?php echo $childrenMenu[$box_no]['pagetitle'];?>                                                    
                                                    </span>  
                                                </span>
                                            <div class="x-clear"></div>
                                        </div>
                                        <div class="x-clear"></div>
                                   </div>
                                    <div class="x-clear"></div>
                                  </div>
                              
                           	</a> 
                           <div class="x-clear"></div>
                        </div> 
                        
                        
                        
                        
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
                         <div class="x-clear"></div>
                       </div><!-- End #widgets-container--> 
                       
                       </div> <!-- End Box -->
                    <span class="clear"></span>
        	</div><!-- END of page_content_right-->
            
	</div><!-- END main-wrap -->
  
</section><!-- END content-container -->
<?php include_once(BOTTOM2);?>
