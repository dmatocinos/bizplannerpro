    <!--JAVASCRIPT SLIDER-->
    <script>
        window.dhx_globalImgPath = "<?php echo PLUGIN_APP_URL;?>/slider/codebase/imgs/";
    </script>
    <script  src="<?php echo PLUGIN_APP_URL;?>/slider/codebase/dhtmlxcommon.js"></script>
    <script  src="<?php echo PLUGIN_APP_URL;?>/slider/codebase/dhtmlxslider.js"></script>
    <script  src="<?php echo PLUGIN_APP_URL;?>/slider/codebase/ext/dhtmlxslider_start.js"></script>
    <link rel="STYLESHEET" type="text/css" href="<?php echo PLUGIN_APP_URL;?>/slider/codebase/dhtmlxslider.css">    

	<!--GOOD FOR THE POP OUT-->
	<script type="text/javascript" src="<?php echo BASE_URL;?>/js/widgetPagesPersonnel.js" ></script>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL;?>/css/widgetPages.css" />
    
     <h1>
           <span class="title" id="chapterName"><?php echo $pageTitle; ?> </span>
    </h1>
    <div class="widget-page-header">
        <h2><a href="<?php echo $_SERVER['PHP_SELF'];?>" class="backtoplan">Back to Outline</a></h2>
    </div>

    <div class="tableBuilder">
          
            <ul class="nav">
                    <li>
                        <a href="#expenses" class="active">
                            <span class="num">
    							1</span>
                            <span class="label" style="width: 120px;">Incoming Payments</span>
                            <span class="clear"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#burden">
                            <span class="num">
   							 2</span>
                            <span class="label" style="width: 120px;">Outgoing Payments</span>
                            <span class="clear"></span>
                        </a>
                    </li>
            </ul>
            <div class="x-clear"></div>
            
            <div class="pages">
                <div class="page" >
                    <div class="page-body">
                        <div class="intro-block ">
                            <div class="widget-content">
                                <h3>About your incoming payments</h3><p>When planning your spending, it is 
                                important to recognize the timing involved in any inbound sales on credit 
                                — what your accountant will call "accounts receivable." If you make a sale 
                                for cash today, that money is immediately available for you to use. If you 
                                agree to invoice your customer for future payment, though, you have to 
                                wait for that payment to come in before you can access the money owed to 
                                you. You can minimize the effect of credit sales by following up with your 
                                customers to ensure you are paid on time and managing your spending to 
                                keep a reasonable buffer in the bank. Otherwise, it's possible to be 
                                profitable on paper and end up going under anyway because the money
                                 owed in is not available in time.</p>
                                 <div class="x-clear"></div>
                            </div>
                        </div>
                    
                    	
                        <?php include_once('incoming_payments.php');?>
                            
                             
                            <div class="page-footer">
                                <div class="left"> 
                                 </div>
                                <div class="right">
                                    <a href="<?php echo $_SERVER['PHP_SELF'];?>"  class="button button-gray">
                                    	<span class="button-cap"><span>I'm Done</span></span></a>
                                    <span class="button button-primary continue"><span class="button-cap"><span>Continue</span>
                                    </span></span>
                                </div>
                            </div>   
                    </div><!--end .page-body-->
           	   	</div><!--end of page-->
                
                <div class="page" >
                    <div class="page-body">
                        <div id="" class="intro-block">
                            <div class="widget-content">
                                <h3>About your outgoing payments</h3><p>Just as slow payments from your customers 
                                will hurt your cash flow, so will fast payments to your suppliers. Think about 
                                the timing of your outgoing payments — what your accountant will 
                                call "accounts payable." Paying for more purchases later instead of 
                                immediately will leave more cash in the bank for your business to work with.</p>
                            </div>
                        </div>
                        <?php include_once('outgoing_payments.php');?>
                        <div id="personnel:j_id291" class="action-links" style="height: 24px;"></div>
                    </div>
                    <div class="page-footer">
                        <div class="left">
                            
                            <!--
                            <a href="javascript:void(0);" class="button button-gray show-preview disabled"><span class="button-cap">
                            <span>Show Preview</span></span></a>-->
                          </div>
                        <div class="right"><a href="<?php echo $_SERVER['PHP_SELF'];?>" class="button button-primary">
                                <span class="button-cap"><span>I'm Done</span></span></a>
                        </div>
                    </div>
                     <span class="clear"></span><br/>  
                </div><!--end of page-->
            </div><!--end of pages-->
            
            
         </div><!--end .tableBuilder-->
        
      
		
		
		<script type="text/javascript">
            //<![CDATA[
            Ext.onReady(function () {
                bpo.widgetPage.personnel.init("Personnel Table");
                bpo.timer.page('widget', 'edit', 'Personnel Budget');
            });
            //]]>
        </script>
 
