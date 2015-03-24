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
                                        	<span><?php echo html_entity_decode($eachSectionData['section_desc']);?></span>
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