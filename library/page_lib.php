<?php
class page_lib
{
	public $allmsgs = array();
	var $color;
	var $DONE = false;
	
	var $columns =''; 
	var $table = '';
	var $where = '';
	
	function __construct(){
		/*
		if ( ! $_SESSION) {
			header('location: ' . "http://".$_SERVER['HTTP_HOST']);
		}
		 */
		
		$this->bpid = $_SESSION['bpId'];
		$this->db = new Database();
		$this->global_func = new global_lib();
		
		
	}
	
	public function updatePageContent($pageid)
	{
		$isGood = false; 
		
		// Initialize FormDate Class to pull out the column query
		$getPageData = new FormData();
		$getPageData->PageFormData('update');
		$setColumns = $getPageData->queryString;

		$where  = "pageid = {$pageid} AND bp_id = {$this->bpid}";
		$table = BP_PAGES_TB;
		
		if($this->db->update($table, $setColumns, $where))
		{
			$isGood = true;
		}
		return $isGood;
	}
	
	public function updateSectionContent($sectionId, $pageId)
	{
		$isGood = false; 
		
		// Initialize FormDate Class to pull out the column query
		$getContentData = new FormData();
		$getContentData->SectionFormData('update');
		$setColumns = $getContentData->queryString;
		
		$bpid = $_SESSION['bpId'];

		$where = "section_id = {$sectionId} and bp_id = {$this->bpid}";

		$table = BP_SECTION_TB;
		
		if($this->db->update($table, $setColumns, $where))
		{
			$isGood = true;
		}
		return $isGood;
	}
	
	
	public function sectionData($pageid)
	{
		$column = "page_sections.section_id AS section_id, s_pageid, section_order, section_desc, section_title, bp_page_sections.section_content AS section_content";
		$where = "s_pageid = {$pageid} AND bp_id = {$this->bpid}";
		$table = SECTION_TB . " JOIN " . BP_SECTION_TB . " USING (section_id)";
		 
		$_data = $this->db->select( $column, $table, $where, "section_order", "", "");
		
		if(count($_data) > 0)
		{
			return $_data;
		}
		else
		{
			return false;
		}	
	}
	
	public function pageContent($pageid)
	{
		$column = "pages.pageid, parentid, pageurl, pagetitle, pageorder, bp_pages.page_content AS page_content";
		$where = "pageid = {$pageid} AND bp_id = {$this->bpid}";
		$table = PAGE_TB . " JOIN " . BP_PAGES_TB . " USING (pageid)";
		 
		$_content = $this->db->select( $column, $table, $where, "", "", "");
		
		if(count($_content) > 0)
		{
			return $_content;
		}
	}
	
	
	public function pageTitle()
	{
		$_pageTitle = $this->getCurrentPageDetails();
		return $_pageTitle[0]['pagetitle'];
	}
	public function getPageId()
	{
		$_pageTitle = $this->getCurrentPageDetails();
		return $_pageTitle[0]['pageid'];
	}
	
	public function getCurrentPageDetails()
	{
		$pageHttpUrl = $this->curPageName();
		$where = "pageurl = '".$pageHttpUrl."'";
		
		$pageDetails  = $this->page('*', $where, '');
		
		if($pageDetails)
		{
			return $pageDetails;
		}
		else
		{
			return false;
		}
	}
	
	
	public function page($column, $where, $order)
	{		
		$COLUMN = $column;
		$WHERE = $where;
		$ORDERASC = $order;
		$pageData = $this->db->select($COLUMN, PAGE_TB, $WHERE, $ORDERASC, "", "");
		if(count($pageData) > 0)
		{
			return $pageData;
		}
		else{return false;	}
		
		
	}
	public function parentDetails($Parentid)
	{
		if(is_numeric($Parentid))
		{
			$where = "pageid = '".$Parentid."'";
			
			$myParentDetails = $this->page('*', $where, '');
			return $myParentDetails;
		}
		
	}
	
	
	public function topMenus()
	{
		$bp_id = $_SESSION['bpId'];

		$select = "pages.*";
		$table = "pages JOIN bp_pages USING (pageid)";
		$where = "parentid = 1 and pageorder > 0 and bp_id = {$bp_id}";
		$orderBy =  'pageorder';
		$getTopMenu = $this->db->select($select, $table, $where, $orderBy);
		if($getTopMenu) 
		{
			return $getTopMenu;
		}
	}

	public function getSections($page)
	{
		$bp_id = $_SESSION['bpId'];

		$where = "s_pageid = {$page['pageid']} AND bp_id = {$bp_id}";
		$from = "page_sections JOIN bp_page_sections USING (section_id)";
		$select = "page_sections.section_id, s_pageid, section_order, section_desc, section_title, bp_page_sections.section_content";
		$sections = $this->db->select($select, $from, $where, "section_order", "", "");
		return $sections;
	}

	public function getMenu($page)
	{
		$bp_id = $_SESSION['bpId'];

		$select = "pages.pageid, pages.parentid, pageurl, pagetitle, pageorder, bp_pages.page_content";
		$table = "pages JOIN bp_pages USING (pageid)";
		$where = "parentid = {$page['pageid']} AND bp_id = {$bp_id}";
		$orderBy =  'pageorder';
		$menu = $this->db->select($select, $table, $where, $orderBy);
		return $menu;
	}
	
	public function getChildMenu()
	{
		$pageDetails = $this->getCurrentPageDetails();
		$where = '';
		$pageId = $pageDetails[0]['pageid'];
		$parentId = $pageDetails[0]['parentid'];
		if($parentId == 1 || $parentId == 0){$childenSql = '';}
		else{$childenSql = "OR parentid = '".$parentId."'";}
		
		// IF page is created in the databse 
		if($pageId && is_numeric($pageId))
		{
			$orderBy =  'pageorder';
			$where = "parentid = '".$pageId."'". $childenSql;
			$childrenMenu = $this->db->select("*", PAGE_TB, $where, $orderBy, "", "");
			if(count($childrenMenu) > 0)
			{
				return $childrenMenu;
			}
		}
		else
		{
			// This means page is not saved in the database
			//$allmsgs[] = 'Page has no id'; $color = 'red';
			//$this->DisplayAllMsgs($allmsgs, $color);
			return ;	
		
		}
			
	}
	
	
	
	
	public function curPageName() {
		$fileName  = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		return substr($fileName, 0, -4);
	}
		
	
	

	public function DisplayAllMsgs($arg1, $arg2)
	{
		if(empty($arg1)){$arg1 = $this->allmsgs;}
		if(empty($arg2)){$arg2 = $this->color;}
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}

	public function getCurrentUser()
	{
		$bp_user_id = $_SESSION['bp_user_id'];
		return $this->db->select("*", 'bp_user', "user_id = {$bp_user_id}");
	}
	
}
?>
