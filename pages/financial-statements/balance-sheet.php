<?php 
	$introText = "";
    $pageTitle = "Balance Sheet";
	include_once("fs_template_top.php");  
	?>
      <a class="intro-block-toggle expanded" href="javascript:void(0);" id="ext-gen13"><span>Hide Instructions</span></a>
                             
                            <div id="introText" class="intro-block dim-action-intro-block" style="display: block; ">
                                <span class="tip"></span>
                                <div class="widget-content"><p>The balance sheet is one of the three standard financial statements. Unlike the profit and loss statement, which measures activities and their effect on profitability during a given period, the balance sheet is a snapshot of the company's financial position as of the last day of the period.</p>

                               <p> How much cash is on hand? What additional value do you have available in major purchases ("assets"), outstanding payments due in from customers, and unsold inventory? How much do you owe for unpaid bills, loan and credit-line payments, and so on? Is the company producing long-term value for its owners or stockholders? Lenders and investors will review the balance sheet carefully to better understand the company's strengths and prospects.</p>
                                
                                <p> Note that the Balance Sheet shown here is not directly editable. It is a read-only display of information from other sources. To change the balance sheet, go to the more detailed tables in the Financial Plan sections, and make changes there. The balance sheet statement will update automatically.</p>
                                                                        
                                    <span class="clear"></span>
                                </div>
                              </div>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                       <p> <?php  $currentPageData->DisplayAllMsgs('','');  ?></p>
                    
    <br/>
    <h5><?php echo $getAllPageDetails[0]['pagetitle'];?></h5>
    
  
    <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
	<?php
    
	 
	 include_once("balance-sheet-include/balancesheet_table.php");
	 
	 
     ?>
     </div>
    
    
    
    
   
<br/><br/><br/><br/>
<?php 
	 if(!empty($_GET['pageid']) && ($_GET['pageid']  == $pageId) && (!empty($_GET['edit'])) && ($_GET['edit'] == "page" ))
	 {
		?>
			<p> <?php echo $introText; ?></p>
			<form method="post" >
				  <div class="rich_textarea">
						<p><textarea id="page_content" name="page_content" type="text">
								<?php echo $getPageContent; ?></textarea></p>
				  </div> 
				 <p><button class="update_page" name="update_page_content" type="submit">
									Save and Continue <?php echo $getPageTitle;?></button></p>									
				 <div class="clearboth"></div>
			  </form>
			 <div class="clearboth"></div>
		 <?php 
	 }
	 else{ 
	 ?>
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
			
	<?php } 
	 }?>
	 
                 
                 
                 
		  <?php
        /*---------------------------------------------------------------
            Get each page secton details 	
        ---------------------------------------------------------------*/
        if($getSectionData)
        {
            // loop through all contents in section and get the details
            foreach($getSectionData as $eachSectionData)
            {
                
                if((isset($_GET['edit'])) && ($_GET['edit'] == "section" ) && (is_numeric($_GET['s_pageid'])) && ($_GET['s_pageid'] == $eachSectionData['section_id']))
                {?>
                    <h3><?php echo $eachSectionData['section_title'];?></h3>
                    <p><?php echo html_entity_decode($eachSectionData['section_desc']);?></p>
                    <form method="post" >
                         <div class="rich_textarea">
                                <textarea id="page_content" name="section_content" type="text" style="width:535px; height:250px;" >
                                        <?php echo $eachSectionData['section_content']; ?></textarea>
                          </div>
                          <br />
                          <input type="hidden" value="<?php echo $eachSectionData['section_id']?>" name="sectionId">
                          
                          <p><button class="update_page" name="update_section_content" type="submit">
                            Update <?php echo $eachSectionData['section_title']?></button></p>
                      </form>		 
                     
                <?php 
                }else
                {
                    if(!empty($eachSectionData['section_content']))
                    {?>
                        <div class="edit_section">
                        <div class="widget_content">
                            <h3><?php echo $eachSectionData['section_title']; ?></h3>
                            <div class="click-to-edit" >
                                <div class="tuck">
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=section&s_pageid=<?php echo $eachSectionData['section_id'];?>&pageid=<?php echo $pageId;?>">
                                        <div class="flag">
                                        <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                                        </div>
                                    </a>
                                </div>
                           </div>
                            <div class="clearboth"></div>
                            
                            <br/>
                            <?php echo html_entity_decode($eachSectionData['section_content']); ?>
                            
                        </div>
                        
                    </div><!--end .edit_section-->
              <?php }
                    else
                    {?>
                         <div class="section">
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=section&s_pageid=<?php echo $eachSectionData['section_id'];?>&pageid=<?php echo $pageId;?>">													<div class="widget text clean-slate">​
                                    <h3><?php echo $eachSectionData['section_title'];?></h3>
                                    <p>Get started on writing this item</p>
                                </div>
                            </a>
                        </div>
                        
                    <?php
                    }
                }
            }// end of foreach
        }// end $getSectionData
    ?>
    
    
                            
                            
<?php include_once("fs_template_bottom.php");  ?>
               
