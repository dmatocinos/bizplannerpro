<div class="expense-budget-entryMethod">
                            <div class="step expense-name">
                                <div class="num">4</div><h4 class="label">How much will you pay back against the principal and when?</h4>
                                
                                   
        						<div class="financial-table period-month financial-year-editor">
                                               <br/>
                                                    <div class="head">
                                                        <div class="row">
                                                               
																 <?php
                                                                    $twelveMonthsData = $cashProjection->twelveMonths("", "");
                                                                    
																	
																	
																	for($e_month = 0; $e_month < count($twelveMonthsData); $e_month++ )
                                                                    {
																		$years = substr($twelveMonthsData[$e_month], -2); 
																		$months = substr($twelveMonthsData[$e_month], 0, 3);
                                                                    ?>
                                                                        <div class="column column-month">
                                                                            <span>&nbsp;<?php echo $months." '".$years; ?></span>
                                                                        </div>
                                                                   <?php
                                                                    }
                                                                    ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="body">
                                                        <div class="row values">
                                                           
                                                            <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_01" value="<?php  echo $allCashProjections[0]['limp_month_01']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                            <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_02" value="<?php  echo $allCashProjections[0]['limp_month_02']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_03" value="<?php  echo $allCashProjections[0]['limp_month_03']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_04" value="<?php  echo $allCashProjections[0]['limp_month_04']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_05" value="<?php  echo $allCashProjections[0]['limp_month_05']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_06" value="<?php  echo $allCashProjections[0]['limp_month_06']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_07" value="<?php  echo $allCashProjections[0]['limp_month_07']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_08" value="<?php  echo $allCashProjections[0]['limp_month_08']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_09" value="<?php  echo $allCashProjections[0]['limp_month_09']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_10" value="<?php  echo $allCashProjections[0]['limp_month_10']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_11" value="<?php  echo $allCashProjections[0]['limp_month_11']; ?>" 
                                                                class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                           <div class="column column-month">
                                                                <div class="td"><input type="text" name="limp_month_12" value="<?php  echo $allCashProjections[0]['limp_month_12']; ?>"
                                                                 class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                   
                                                    <div class="financial-table period-year financial-year-editor">
                                                <div>
                                                    <div class="head">
                                                        <div class="row">
														   <?php
                                                                $yrsOfFinancialForecast = $cashProjection->financialYear();
                                                                for($e_yr = 0; $e_yr < count($yrsOfFinancialForecast); $e_yr++ )
                                                                 {
                                                                ?>	
                                                                    <div class="column column-year">
                                                                        <div class="td">FY<?php echo $yrsOfFinancialForecast[$e_yr]; ?></div>
                                                                    </div>
                                                             <?php } ?>   
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="body">
                                                        <div class="row vales">
                                                            <div class="column column-year column-total NUMBER SUM" style="float: left;" rel="0">
                                                                <div class="td">
                                                                    <p class="display-only"><?php  echo $currency.$allCashProjections[0]['financial_payment'][0]['lip_total_per_yr']; ?></p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="column column-year" rel="1" id="ext-gen29">
                                                                <div class="td">
                                                                    <input type="text" name="lip_total_per_yr2" value="<?php  echo $allCashProjections[0]['financial_payment'][1]['lip_total_per_yr']; ?>" 
                                                                    	class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14" >
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="column column-year" rel="2" id="ext-gen30">
                                                                <div class="td">
                                                                    <input type="text" name="lip_total_per_yr3" value="<?php  echo $allCashProjections[0]['financial_payment'][2]['lip_total_per_yr']; ?>" 
                                                                    	class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14">
                                                                </div>
                                                            </div>
                                                        </div>
                                                         <span class="clear"></span>
                                                    </div>
                                                </div>
                                           </div>
                                               </div>                           
      	                      </div>
                            
                            <div class="x-clear"></div>
                        </div>