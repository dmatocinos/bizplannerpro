                            <h1>
                                <span class="edit">
                                   <!-- <span class="outline-num">1.</span>-->
                                    <span class="title" id="chapterName"><?php echo $pageTitle; ?></span>
                                </span>
                            </h1>
                       		 <a class="intro-block-toggle expanded" href="javascript:void(0);" id="ext-gen13"><span>Hide Instructions</span></a>
                             
                            <div id="introText" class="intro-block dim-action-intro-block" style="display: block; ">
                                <span class="tip"></span>
                                <div class="widget-content"><p>Sometimes regular sales are not enough to fund growth, especially 
                                for startups. Will your business need additional funding to balance your budget, finance major 
                                purchases, or meet other objectives? This section makes it easy to determine your funding needs
                                 and to build loans, investments, credit lines, credit cards, or less-specific funding 
                                 sources into your plan.</p>
                                <span class="clear"></span>
                                </div>
                              </div>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                           	
                            <?php
                            	/*-------------------------------------------------------------
									if $_loanInvestment object returns true
								--------------------------------------------------------------*/
								
								$_loanInvestment = new loansInvestments_lib();
								$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
								
								if($allloanInvestmentProjection)
								{
									include_once("projection_table.php");
								}
								/*-----else if $_loanInvestment object returns false-------*/
								else
								{
							?>
                            <div class="section">
                                         <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?projection=_loan_invest">
                                            <div class="widget text clean-slate">​
                                                    <h3><?php echo $pageTitle; ?> Table</h3>
                                                    <p>Launch the step-by-step table builder</p>
                                            </div>
                                        </a>
                                    </div>
                            
                            <?php }// ---- 	end of  if $expenditure -------------------?>
                               
                              
                         
              				<!----------------------------------------------
                            	ABOUT Loans And Investments SECTION
                             -------------------------------------------- -->
                           <!--<?php include_once(BASE_PATH."/include/pageContentEditor.php"); ?>-->
  		                       <?php include_once(BASE_PATH."/include/pageSectionEditor.php"); ?>
                          
						   