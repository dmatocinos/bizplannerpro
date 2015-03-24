
                            <h1>
                                <span class="edit">
                                   <!-- <span class="outline-num">1.</span>-->
                                    <span class="title" id="chapterName"><?php echo $pageTitle; ?></span>
                                </span>
                            </h1>
                       		<p>&nbsp;</p>
                           	
                            <?php
                            	$employee = new employee_lib();
								$allEmpDetails = $employee->getAllEmployeeDetails2("", "", "");
								if($allEmpDetails)
								{
									?>
                                     <div id="personal_table">
                                     <div class="edit_section">
                                        <div class="widget_content">
                                            <h3>Personnel Plan</h3>
                                            <div class="clearboth"></div>
                                        </div>
                                        <div class="click-to-edit" >
                                          
                                            <div class="tuck">
                                                <a href="<?php echo $_SERVER['PHP_SELF'].'?personnel=_employee'?>">
                                                    <div class="flag">
                                                    <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                                                    </div>
                                                </a>
                                            </div>
                                          
                                       </div>
                                    </div><!--end .edit_section-->
                                    
                                    
                                     <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" 
                                     		class="preview-table salesForecast-preview table-4-columns">
                                       <?php 
									   	 //print_r($allEmpDetails);
                                        ?>
										
                                        <div class="row row-header singleline">
                                             <span class="cell label column-0 singleline">
                                                  <p class="overflowable"> </p>
                                            </span>
                                           <?php // loop through and pick out the years
                                            foreach ($allEmpDetails[0]['financial_status'] as $eachFinStat)
                                            {?>
                                                <span class="cell data column-1 singleline">
                                                      <p class="overflowable">FY<?php echo $eachFinStat['financial_year']; ?></p>
                                                </span>
                                            <?php 
                                            }
                                            ?>
											<div class="x-clear"></div>
                                        </div><!--end .singleline-->
                                            
                                           
										<?php
										$counter = 0;
										$arraySummation = "";
                                        foreach($allEmpDetails as $empDetails)
                                        {?>
                                            <div class="row row-item singleline"> 
                                                <span class="cell label column-0 singleline">
                                                  <p class="overflowable"><?php echo $empDetails['emplye_name']?></p>
                                                </span>
                                            <?php 
                                            foreach($empDetails['financial_status'] as $finDetails)
                                            {?>
                                                <span class="cell data column-1 singleline">
                                                      <p class="overflowable"><?php echo $employee->defaultCurrency.$finDetails['total_per_yr']; ?></p>
                                                </span>
                                                
                                            <?php 
											}
											?>
                                            <div class="x-clear"></div>
                                            </div>		 
                                            
                                            
												<?php 
												
												for($i=0; $i< count($empDetails['financial_status']); $i++)
                                                {
                                                     $arraySummation[$i][$counter]  = $empDetails['financial_status'][$i]['total_per_yr'];
                                                 }
												 $counter = $counter+1;
											
										} ?>
                                        
								 <div class="row row-group_footer singleline">
                                                  <span class="cell label column-0 singleline">
                                                          <p class="overflowable">Total</p>
                                                  </span>
										<?php
                                        foreach($arraySummation as $total)
										{?><span class="cell data column-1 singleline">
                                                          <p class="overflowable">
                                                          
											<?php echo $employee->defaultCurrency.array_sum($total); ?>	
                                            </p>
                                                  </span>
                                       <?php            
										}
										?>
                                        <div class="x-clear"></div>
                                            </div>
                                        
                                        
                                       	</div><!--end of .widgetForm-->
                                		<div class="x-clear"></div>
                            		</div><!--end #personal_table-->
                            
								<?php
								}// end of if($allEmpDetail)
								
								
								
								else
								{
							?>
                            	<div class="section">
                                         <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?personnel=_employee">
                                            <div class="widget text clean-slate">​
                                                    <h3>Personnel Table</h3>
                                                    <p>Launch the step-by-step table builder</p>
                                            </div>
                                        </a>
                                    </div>
                            
                            <?php } ?>
                            <p>&nbsp;</p>
                              
                               
                             
        
        
              			<!--ABOUT Sale forecast-->
                            <div class="clearboth"></div>
                 			<p>&nbsp</p>
                            <p> <?php  $currentPageData->DisplayAllMsgs('','');  ?></p>
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
                        
                
