 <?php 
 	session_start();
	
 	include_once("head.php");
	
	/*------- Select one Business Plan and it allows the load data in session	----------*/
		//$BPSettings->getOneBusinessPlan();	
	/*-------------------------------------------------------------------------------------*/
	
?>
<body class="page page-id-673 page-template page-template-page-template-full-width-php">
	<aside class="top-aside clearfix">
		<div class="center-wrap">
			<div class="one_half">
							</div><!-- END top-toolbar-left -->
            
			<div class="one_half">
							</div><!-- END top-toolbar-right -->
		</div><!-- END center-wrap -->
    <div class="top-aside-shadow"></div>
	</aside>
 
<header>
<div>
	<div class="center-wrap"> 
		<div class="companyIdentity">
    		<img src="shortcodes_files/images/BizPlannerPro_400px.png" width="400" height="53" border="0">
		  </div><!-- END companyIdentity -->    
			<!--
            <div class="help_button">
                        	<p><a href="">help &amp; support </a></p>
             </div>
             -->
	</div>
	<!-- END center-wrap -->
    
    	<!--end .main-menu-->
  </div>  
</header>
<style type="text/css">
	header{
		padding:32px 0 0 0;
	}
	
	header nav a {
		color:#fff;	
	}
	header nav a:hover,  header nav .current_page_parent a,  header nav .current-menu-ancestor  a, header nav .current-menu-item a{
		color:#9e8e68
	} 
	
	/*----- register intesret Form on home page*/

	
	.home_form h4{
		font-size:18px;
		line-height:28px
	}

	 form#interest{
       		 margin: 13px 0 0 0px ;
        	padding: 0;
        	font-size: 14px;
        	line-height: 20px;
    	}
	
	 form#interest p label {
		display: block;
		float: left;
		margin-right: 10px;
		width: 30%;
		vertical-align: middle;
		margin-top: 0;
		font-size:14px;
	}
	form#interest p button, p.register_business_button button{
		
		font-size: 14px;
		cursor: pointer;
		padding: 5px 10px;

		border: 1px solid #ADC671;
		color:#5D7731;
		
		background: -webkit-gradient(linear, 0 0, 0 bottom, from(#c8e183), to(#a1cc59));
		background: -moz-linear-gradient(#c8e183, #a1cc59);
		background: linear-gradient(#c8e183, #a1cc59);
		-pie-background: linear-gradient(#c8e183, #a1cc59);
		background-image: -o-linear-gradient(#c8e183, #a1cc59);

		position: absolute;
		text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
		z-index:999;

		-webkit-border-radius: 3px;
		border-radius: 3px;
		border-width: 1px;
		border-style: solid;
		font-weight:bold;
		width:140px;
		text-transform:uppercase;
	}
	
	p.register_business_button button{
		width:178px;
	}

	form#interest p button:hover, p.register_business_button button:hover{
		cursor: pointer;
		background: #a1cc59;
		background: -webkit-gradient(linear, 0 0, 0 bottom, from(#c8e183), to(#c8e183));
		background: -moz-linear-gradient(#a1cc59, #c8e183);
		background: linear-gradient(#a1cc59, #c8e183);
		-pie-background: linear-gradient(#a1cc59, #c8e183);
		background-image: -o-linear-gradient(#a1cc59, #c8e183);
			
		}
	
	form#interest input{
		padding:0;	
		border: 1px solid #CFCFCF;
		width: 50%;	
		border: 1px solid #CFCFCF;
		width: 50%;
		background-color: #F4F4F4;
		border: 1px solid;
		font-family: "Lucida Grande", "Lucida Sans Unicode", Arial, Verdana, sans-serif;
		font-size: 15px;
		margin-bottom: 0;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		border-width: 1px;
		border-style: solid;
		border-color: #CCC #E6E6E6 #E6E6E6 #CCC;
		padding: 4px;
		color:#000;
	}

form#interest p select{
	border-color: #CCC #E6E6E6 #E6E6E6 #CCC;
	padding: 4px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	border-width: 1px;
	border-style: solid;
	font-size: 15px;
	height: 26px;
	width: 205px;
	color: black;
	
}
	
</style>
<?php include_once("header.php");?>
<section class="small_banner">
<!-- pop menu -->
		
		
<!-- End Pop Menu -->




<div class="center-wrap">
<p class="page-banner-heading"><?php echo $_SESSION['bpName'];?></p>


<p class="page-banner-description" id="banner-description-673">&nbsp;</p>

<div class="breadcrumbs"><a href="<?php echo plan?>">Plan</a> → <span class="current_crumb"><?php echo $_SESSION['bpName'];?></span></div><!-- END breadcrumbs -->
</div>
<!-- END center-wrap -->

<div class="shadow top"></div>
<div class="shadow bottom"></div>
<div class="tt-overlay"></div>
</section>
<!-- Plan Box -->
<div class="planBox">     
	<div id="header-tab">
	<div id="headerForm:sublinks">
		<ul id="secondary-links">
            <?php if ($_SESSION['login_type'] != 'client') : ?>
			<li class="edit-context-state"><a  href="<?php echo BASE_URL. '/print';?>"><span>Print</span></a>
			</li>
            <?php endif; ?>
			<li class="edit-context-state active"><a  href="<?php echo $_SERVER['REQUEST_URI'];?>"><span>Plan</span></a>
			</li>
			<!--<li class="sharing-context-state"><a href="#"><span>Scoreboard</span></a>
			</li>-->
			<!--<li class="sharing-context-state"><a href="#"><span>Settings</span></a>
		    </li>-->
		</ul>
	</div>
	</div>
</div>
  <!-- icon-->
 <!--<div style="margin:0 auto; width:960px;border-bottom: 1px solid #B4B4B4; height: 35px;">
            <ul id="ternary-links">
                    <li class="edit-context-state active"><a  href="#"><span>Plan View</span></a>
                    </li>
                    <li class="cover-context-state"><a  href="#"><span>Cover Page</span></a>
                    </li>
                    <li class="publish-context-state"><a  href="#"><span>Print</span></a>
                    </li>
                    <!--<li class="settings-context-state"><a href="#"><span>Settings</span></a>
                    </li>
            </ul>
        </div>-->
        
        
      
	<?php include_once (text_editor_url);?>
        
