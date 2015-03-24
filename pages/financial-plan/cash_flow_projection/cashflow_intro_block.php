
<style>
	.edit_section .widget_content {
		
		margin-bottom: 20px;
	}

</style>

							 <h1>
                                <span class="edit">
                                   <!-- <span class="outline-num">1.</span>-->
                                    <span class="title" id="chapterName"><?php echo $pageTitle; ?></span>
                                </span>
                            </h1>
                       		
                             <a class="intro-block-toggle expanded" href="javascript:void(0);" id="ext-gen13"><span>Hide Instructions</span></a>
                             
                            <div id="introText" class="intro-block dim-action-intro-block" style="display: block; ">
                                <span class="tip"></span>
                                <div class="widget-content"><p>Managing cash flow is one of the most important 
                                aspects of business. In the planning phase, you can radically change your cash 
                                outlook by adjusting a few basic assumptions about when you pay and get paid. 
                                This section, which is a required part of full financials, walks you through those
                                 assumptions, which are also available in the plan settings.</p>
                                <span class="clear"></span>
                                </div>
                              </div>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <?php
                            	if($getPayments)
								{
									?>
                                     <div id="personal_table">
                                     <div class="edit_section">
                                        <div class="widget_content">
                                            <h3><?php echo $pageTitle; ?></h3>
                                            <div class="clearboth"></div>
                                        </div>
                                        <div class="click-to-edit" >
                                          
                                            <div class="tuck">
                                                <a href="<?php echo $_SERVER['PHP_SELF'].'?cashflow=payments'?>">
                                                    <div class="flag">
                                                    <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                                                    </div>
                                                </a>
                                            </div>
                                          
                                       </div>
                                    </div><!--end .edit_section-->
                                    
                           <div class="clearboth"></div>        
                             <!-----------------------------------------------------
                                CASH FLOW PROJECTION
                            ------------------------------------------------------>
                            <div id="widgetForm:j_id278:j_id287:preview-table-wrapper" class="preview-table-wrapper" style="overflow: hidden; padding: 0px; width: 597px; ">
                              <div class="jspContainer" style="width: 597px; height: 195px; ">
                                <div class="jspPane" style="padding: 0px; top: 0px; width: 597px; ">
                                  <div class="preview-table-wrapper-inner" style="width: 597px; ">
                                  <div id="widgetForm:j_id278:j_id287:j_id289:0:preview-table-table" class="preview-table salesForecast-preview table-2-columns cash_flow_assumptions">  
                                     <div class="row row-header singleline">
                                              <span class="cell label column-0 singleline">
                                                      <p class="overflowable">Cash Inflow</p>
                                              </span>
                                              <span class="cell data column-1 singleline">
                                                      <p class="overflowable"> </p>
                                              </span>
                
                                            <div class="x-clear"></div>
                                        </div>
                                        <div class="row row-group_item singleline">
                                                  <span class="cell label column-0 singleline">
                                                    <p class="overflowable">% of Sales on Credit</p>
                                                  </span>
                                                  <span class="cell data column-1 singleline">
                                                    <p class="overflowable"><?php echo $getPayments[0]['percentage_sale']?>%</p>
                                                  </span>
                    
                                            <div class="x-clear"></div>
                                        </div>
                                        <div class="row row-group_last_item singleline">
                                                  <span class="cell label column-0 singleline">
                                                    <p class="overflowable">Avg Collection Period (Days)</p>
                                                  </span>
                                                  <span class="cell data column-1 singleline">
                                                  	<p class="overflowable"><?php echo $getPayments[0]['days_collect_payments']?></p>
                                                  </span>
                    
                                            <div class="x-clear"></div>
                                        </div>
                                        <div class="row row-group_header singleline">
                                                  <span class="cell label column-0 singleline">
                                                          <p class="overflowable">Cash Outflow</p>
                                                  </span>
                                                  <span class="cell data column-1 singleline">
                                                          <p class="overflowable"> </p>
                                                  </span>
                    
                                            <div class="x-clear"></div>
                                        </div>
                                        <div class="row row-group_item singleline">
                                                  <span class="cell label column-0 singleline">
                                                          <p class="overflowable">% of Purchases on Credit</p>
                                                  </span>
                                                  <span class="cell data column-1 singleline">
                                                          <p class="overflowable"><?php echo $getPayments[0]['percentage_purchase']?>%</p>
                                                  </span>
                    
                                            <div class="x-clear"></div>
                                        </div>
                                        <div class="row row-group_last_item singleline">
                                                  <span class="cell label column-0 singleline">
                                                          <p class="overflowable">Avg Payment Delay (Days)</p>
                                                  </span>
                                                  <span class="cell data column-1 singleline">
                                                          <p class="overflowable"><?php echo $getPayments[0]['days_make_payments']?></p>
                                                  </span>
                    
                                            <div class="x-clear"></div>
                                        </div>
									</div>
                                    <div class="x-clear"></div>
                            </div></div></div></div>
                            
								<?php
								}// end of if($getPayments)
								else
								{
							?>
                            	<div class="section">
                                         <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?cashflow=payments">
                                            <div class="widget text clean-slate">​
                                                    <h3><?php echo $pageTitle; ?></h3>
                                                    <p>Launch the step-by-step table builder</p>
                                            </div>
                                        </a>
                                    </div>
                            
                            <?php } ?>
                            
              			<!--ABOUT Sale forecast-->
                            <div class="clearboth"></div>
                 			 <?php if( !empty($_GET['edit']) && !empty($_GET['pageid']) ){ ?>
                                <form method="post">
                                     <div class="rich_textarea">
                                            <textarea id="page_content" name="page_content" type="text" style="width:535px; height:250px;" >
													<?php echo $getPageContent; ?></textarea>
                                      </div>
                                      <br />
                                      <button class="update_page" name="update_page_content" type="submit">Update</button>
                                  </form>
                       		 <?php }else{ ?>
                        			<?php if(!empty($getPageContent)){?>
                                        <div class="edit_section">
                                            <div class="widget_content">
                                                <h3><?php echo $getPageTitle; ?></h3>
                                                <br/>
                                                <?php echo html_entity_decode( $getPageContent); ?>
                                                <div class="clearboth"></div>
                                            </div>
                                            <div class="click-to-edit" >
                                              
                                                <div class="tuck">
                                                    <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?edit=page&amp;pageid=<?php echo $pageId;?>">
                                                        <div class="flag">
                                                        <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                                                        </div>
                                                    </a>
                                                </div>
                                              
                                           </div>
                                        </div><!--end .edit_section--><p>&nbsp</p><p>&nbsp</p>
                                    <?php }else{?>
                                    <div class="section">
                                         <a href="<?php echo "http://".$_SERVER['HTTP_HOST']."/".$_SERVER['PHP_SELF']; ?>?edit=page&amp;pageid=<?php echo $pageId;?>">
                                            <div class="widget text clean-slate">​
                                                    <h3><?php echo $getPageTitle;?></h3>
                                                    <p>Get started on writing this item</p>
                                            </div>
                                        </a>
                                    </div>
                                    
                            <?php } ?>
                           
						  <?php } ?>
                        
                   
                 