<style>
	.support_nav li.selector ul li a {
		color: #3380B6;
		display: block;
		float: none;
		font-weight: normal;
		line-height: 26px;
		white-space: nowrap;
		margin: 0;
		padding: 2px 20px;
	}
	.support_nav li.my-plans div.plans ul li.selectableItem a {
		width: 247px;
		text-overflow: ellipsis;
		overflow: hidden;
	}
	.support_nav li.selector ul li:first-child {
		-moz-border-radius-topleft: 4px;
		-webkit-border-top-left-radius: 4px;
	}
	
</style>
                    
                    <!--<a href="javascript:void(0);" class="beta-feedback" onclick="bpo.showFeedback();">Give Feedback</a>-->
                    <div class="support_nav">
                        <ul class="block_list">
                            
                            <li class="selector my-plans">
                                <div class="selector-top">
                                    <a href="javascript:void(0);" class="menu-arrow">
                                        <span class="title fixed-title" style="width: 53px;">My Plans</span>
                                        <span class="arrow"></span>
                                    </a>
                                </div><div id="headerForm:my-plans-menu" class="link-drop-down">
                                     <div id="" class="white plans">   
                                        <ul>
                                           	<?php foreach($allBp as $dpDetails){?>
												<li class="selectableItem"><a href="<?php echo executive_summary_url ?>?bp=<?php echo $dpDetails['bp_id']?>" class="plan-title">
                                                    <?php echo $dpDetails['bp_name']?></a></li>
											<?php } ?>	
											<li class="bottom">
                                                <span class="selectableItem new-plan" style="border-right:1px #E1E1E1 solid; width:118px;">
                                                        <a href="<?php echo BASE_URL; ?>/plan" class="button button-primary">
                                                            <span class="button-cap">
                                                                <span>New Plan</span>
                                                            </span>
                                                        </a>
                                                </span>
                                                <span class="view-all">
                                                    <!--<a href="#" onclick="bpo.changePage(true);">View All Plans</a>-->
                                                    <a href="<?php echo BASE_URL; ?>/exit" class="button button-primary exit">
                                                            <span class="button-cap">
                                                                <span>Exit</span>
                                                            </span>
                                                        </a>
                                                </span>
                                                <div class="x-clear"></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                         </ul>
                    </div>  
                    
                   
                
                

                
                