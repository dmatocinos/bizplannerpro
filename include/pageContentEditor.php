 <div class="clearboth"></div>
                 			<p>&nbsp</p>
                            <p> <?php  $currentPageData->DisplayAllMsgs('','');  ?></p>
                             <?php if( !empty($_GET['edit']) && !empty($_GET['pageid']) )
							 { ?>
                                <form method="post">
                                     <div class="rich_textarea">
                                            <textarea id="page_content" name="page_content" type="text" >
													<?php echo $getPageContent; ?></textarea>
                                      </div>
                                      <br />
                                      <button class="update_page" name="update_page_content" type="submit">Update</button>
                                  </form>
                       		 <?php }else{ ?>
                        			<?php if(!empty($getPageContent))
										{?>
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
                                    
								<?php } // end else ?>
                           
						<?php } // end else ?>
						