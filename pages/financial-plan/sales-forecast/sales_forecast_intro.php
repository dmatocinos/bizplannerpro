
  
                            <h1>
                                <span class="edit">
                                   <!-- <span class="outline-num">1.</span>-->
                                    <span class="title" id="chapterName"><?php echo $pageTitle; ?></span>
                                </span>
                            </h1>
                            
                            	
                            
                            <a class="intro-block-toggle" href="javascript:void(0);" id="ext-gen13"><span>Show Instructions</span></a>
                             
                            <div id="introText" class="intro-block dim-action-intro-block" style="display: none; ">
                                <span class="tip"></span>
                                <div class="widget-content"><p>The financial future of your business begins with your projected
                                            sales. This is just your best guess at how much revenue your
                                            business will generate in the coming years. Think of your
                                            forecasted sales as the paycheck for your company. Just like at
                                            home, you can't really dial in your budget until you know
                                            roughly how much money you'll have to spend. That's what the
                                            forecast provides.</p>
                                        <p>The future is uncertain, of course. No one knows exactly how your
                                            business will do. The best anyone can do is an educated guess.
                                            Knowing all that you know about your particular business, though,
                                            your educated guess is actually worth a lot.</p>
                                        <p>The table builder will walk you through the details one step at a
                                            time. Be realistic here. Your business plan does not need to shoot
                                            for the sort of ambitious targets that you might share with your
                                            sales staff as motivational goals. The numbers in the sales
                                            forecast should be reasonable, maybe even a bit conservative, so
                                            that you can achieve them and stay on plan.</p>
                                		
                                    <span class="clear"></span>
                                </div>
                              </div>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            
                           	
                            <?php
							
							
							
							
                            	$sales = new sales_forecast_lib();
								$allSalesDetails = $sales->getAllSales("", "", "");
								if($allSalesDetails)
								{
									include_once("sales-forecast/sales_forecast_table.php");
									?>
                                <?php
									
									include_once("sales-forecast/gross_margin_monthly_graph.php");
									
									
									
								}// end of if($allEmpDetail)
								else
								{
							?>
                            <div class="section">
                                         <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?table=forecast">
                                            <div class="widget text clean-slate">​
                                                    <h3>Sales Forecast Table</h3>
                                                    <p>Launch the step-by-step table builder</p>
                                            </div>
                                        </a>
                                    </div>
                            
                            <?php } ?>
                                    
                                
                                     
                            
        
        
              			<!--ABOUT Sale forecast-->
                            <div class="clearboth"></div>
                 			<p>&nbsp</p>
                            <p> <?php  $currentPageData->DisplayAllMsgs('','');  ?></p>
                             <?php if( !empty($_GET['edit']) && !empty($_GET['pageid']) ){ ?>
                                <form method="post">
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
                        
                      
                        
                       