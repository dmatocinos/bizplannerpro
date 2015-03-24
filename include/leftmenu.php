<?php
	$currentPageParentid='';
	
	$pageDetails = new page_lib();
 	$currentPageUrl = $pageDetails->curPageName();
 	$currentPageId = $pageDetails->getPageId();
	
	
	
	$currentPageDetails = $pageDetails->getCurrentPageDetails();
	
	if($currentPageDetails)
	{
		$currentPageParentid = $currentPageDetails[0]['parentid'];
		
		// I will need this later
		$CurrentPageParentDetails = $pageDetails->parentDetails($currentPageParentid);
		
		//print_r($CurrentPageParentDetails);
	}
	
	
	/***************************************
		TOP MENU
	****************************************/
	$topmenu = $pageDetails->topMenus();
	$changeChapterUrl = "";
	$parentPageUrl = "";
	
	$nextUrl = "";
	$child_pageOrder = "";
	
	
	$parent_pageOrder = "";
	
	$homeUrl = '';
	$selected_li = array();
	$selected_a = array();
	
	//$allTopMenu="<li><a href='".BASE_URL.'/'.$homeUrl."'>Home</a></li> ";
	
	$allTopMenu="";
	/*---------------------------------------------------------------------
		TOSIN:
		REPLACE THE FOR BELOW WITH for($i = 0; $i < count($topmenu); $i++ ) 	
		IN ORDER TO DISPLAY THE APPENDIX
	------------------------------------------------------------------------*/
	for($i = 0; $i < (count($topmenu) - 1); $i++ ) 
	{
		$selected_li[$i]['pageid'] = 'not-selected';
		$selected_span[$i]['pageid'] = 'not-selected';
		
		if ($topmenu[$i]['pageurl'] == 'financial-statements' && $_SESSION['login_type'] == 'client') {
            continue;
        }
		
		// If current parent Page or children page
		if($currentPageUrl == $topmenu[$i]['pageurl'] || $currentPageParentid == $topmenu[$i]['pageid'])
		{
			$parentFolderName = $topmenu[$i]['pageurl'];
			$selected_li[$i]['pageid'] = 'current_subpage';
			$selected_span[$i]['pageid'] = 'executive';

			/***************************************
				SIDE MENU
			****************************************/
			$childrenMenu = $pageDetails->getChildMenu();
			$activeChildmenu = array();
			$allChildrenMenu = '';
			$childCounter = 0;
			$childNextUrl = "NOT YET ASSIGNED";
			
			$parentUrlCounter = $i;
			
			$parentUrlCounter = $parentUrlCounter+1;
			if((int)($parentUrlCounter) == (int)count($topmenu))
			{	
				// after the last parent page
				$parentNextUrl = "no-parent-url";
				$parentPageUrl = $topmenu[$i]['pageurl'];
			}
			else
			{
				$parentPageUrl = $topmenu[$i]['pageurl'];
				$parentNextUrl = $topmenu[$parentUrlCounter]['pageurl'];
			}
			
			if(count($childrenMenu) >0)
			{
				// if Parent Page has a child next Url will the his first child
				$nextUrl = $parentPageUrl.'/'.$childrenMenu[$childCounter]['pageurl'];
				
				$allChildrenMenu .= "<ul>";
				
				foreach($childrenMenu as $childMenu)
				{
					$childUrlCounter = 0;
					$childCounter = $childCounter+1;
					
					
					if($currentPageId == $childMenu['pageid'])
					{
						$childUrlCounter = $childCounter;
						
						if((int)($childUrlCounter) == (int)count($childrenMenu))
						{
							// assign the parent URL
							$nextUrl = $parentNextUrl;
						}
						else
						{
							// Add one to the current page to make give the next page url
							$childNextUrl = $childrenMenu[$childUrlCounter]['pageurl'];
							
							$nextUrl = $parentPageUrl.'/'.$childNextUrl;
						}
						
						// activate child
						$activeChildMenu[$childCounter] = "active";
						
						// deactivate parent arrow
						$selected_span[$i]['pageid'] = '';
					}
					else
					{
						$activeChildMenu[$childCounter] = '';
					}
					$allChildrenMenu .= "<li class='".$activeChildMenu[$childCounter]."'><a class='section' href='".BASE_URL.'/'.$parentFolderName."/".trim($childMenu['pageurl'])."'>". 
					$childMenu['pagetitle']."</a></li>";
						
				}
				$allChildrenMenu .= "</ul>"; 
				
				
			}
			//End of Child menu	
		}
		else
		{
			$allChildrenMenu = "";	
		}
		
		// Dipslay Main menu begins
		$allTopMenu .= "<li class='".$selected_li[$i]['pageid']."'>
							
							<div class='title ".$selected_span[$i]['pageid']."'>
							
								<!--<a class='function chapter-title'>
									<span></span>
								</a>-->
								
								<a href='".BASE_URL.'/'.$topmenu[$i]['pageurl']."'><span></span>".$topmenu[$i]['pagetitle'].'</a></div>'.$allChildrenMenu.'</li>';
	}

	if ($_SESSION['bpId'] && (!isset($_SESSION['login_type']) || $_SESSION['login_type'] != 'client')) {
		
		$allTopMenu .= "<li>
			
		<div class='title'>
				
		<a href='".BASE_URL.'/plandetail'."'><span></span>Plan Details".'</a></div></li>';
		
	}
	
	
?>
	
   
		<?php echo $allTopMenu; ?>
 