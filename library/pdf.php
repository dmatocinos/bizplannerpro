<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
require_once(TCPDF_PATH . 'tcpdf.php');

// Include the main TCPDF library (search for installation path).
//require_once('config/tcpdf_config.php');
//require_once('tcpdf.php');

class TOC_TCPDF extends TCPDF {

	/**
 	 * Overwrite Header() method.
	 * @public
	 */
	 
	public $numnormalpages = 0;
	public $plantitle = '';	
	
	public function Header() {
	
		$pagetext = intval($this->getAliasNumPage());
	
		if ($this->tocpage) {
			// *** replace the following parent::Header() with your code for TOC page
					
			
			$pagetext = $this->getRoman($this->getNumPages()- $this->numnormalpages);
			
			
			
			//$this->getNumPages()." - ".$this->numnormalpages 
			
			//parent::Header();
		} else {
			// *** replace the following parent::Header() with your code for normal pages
			//parent::Header();
			
			$t = intval($this->getAliasNbPages());
			$p = intval($this->getAliasNumPage());
			
			$pagetext = $this->getNumPages()-1;
			
			
		}
			$img = ($this->CurOrientation=='P'?'head-bg.jpg':'head-bg-l.jpg');
			$w = ($this->CurOrientation=='P'?215.9:279.4);
		
			$this->Image(PDF_IMAGES_PATH.$img, 0, 0, $w,25.4, 'JPEG',null ,null ,2);
			
			$this->setTextColor(255, 204, 51);			
			$this->SetFont('rockb', '', 12, '', true);
			$this->MultiCell(0, 5, $this->plantitle, 0, 'L', 0, 0, '', 15, true);
			$this->SetFont('rockb', '', 9, '', true);
			$this->MultiCell(0, 5, $pagetext, 0, 'R', 0, 0, '', 15, true);
		
	}

	/**
 	 * Overwrite Footer() method.
	 * @public
	 */
	public function Footer() {
		if ($this->getNumPages() == 1) {
			return;					
		} 
		
		// *** replace the following parent::Footer() with your code for TOC page
			$this->SetY(-24);										
			$this->SetTextColorArray(array(0,0,0));
			//set style for cell border
			
			//$this->SetY(-20);
			$this->SetFont('FRABK', '', 7.5, '', true);
			
			$html = '<div style="padding-top: 15px; border-top: 1px solid #000"><p><span style="font-family: helvetica; font-weight: bold">CONFIDENTIAL - DO NOT DISSEMINATE.</span> This business plan contains confidential, trade-secret information and is shared only with the
understanding that you will not share its contents or ideas with third parties without the express written consent of the plan author.</p></div>';
			
			
			//$this->Cell(0, 25, $html, 0, 1, 'L');
			
			$this->writeHTMLCell(0, 20, '', '',$html, 0, 0, 0, true ,'L' );
		
	}
	
	public function getRoman($num) {
		$n = intval($num);
		$res = '';

		/*** roman_numerals array  ***/
		$roman_numerals = array(
			'm'  => 1000,
			'cm' => 900,
			'd'  => 500,
			'cd' => 400,
			'c'  => 100,
			'xc' => 90,
			'l'  => 50,
			'xl' => 40,
			'x'  => 10,
			'ix' => 9,
			'v'  => 5,
			'iv' => 4,
			'i'  => 1);

		foreach ($roman_numerals as $roman => $number){
			/*** divide to get  matches ***/
			$matches = intval($n / $number);

			/*** assign the roman char * $matches ***/
			$res .= str_repeat($roman, $matches);

			/*** substract from the number ***/
			$n = $n % $number;
		}

		/*** return the res ***/
		return $res; 
	}
	
	
	
	/**
	 * Output a Table of Content Index (TOC).
	 * This method must be called after all Bookmarks were set.
	 * Before calling this method you have to open the page using the addTOCPage() method.
	 * After calling this method you have to call endTOCPage() to close the TOC page.
	 * You can override this method to achieve different styles.
	 * @param $page (int) page number where this TOC should be inserted (leave empty for current page).
	 * @param $numbersfont (string) set the font for page numbers (please use monospaced font for better alignment).
	 * @param $filler (string) string used to fill the space between text and page number.
	 * @param $toc_name (string) name to use for TOC bookmark.
	 * @param $style (string) Font style for title: B = Bold, I = Italic, BI = Bold + Italic.
	 * @param $color (array) RGB color array for bookmark title (values from 0 to 255).
	 * @public
	 * @author Nicola Asuni
	 * @since 4.5.000 (2009-01-02)
	 * @see addTOCPage(), endTOCPage(), addHTMLTOC()
	 */
	public function addTOC($page='', $numbersfont='', $filler='.', $toc_name='TOC', $style='', $color=array(0,0,0)) {
		$fontsize = $this->FontSizePt;
		$fontfamily = $this->FontFamily;
		$fontstyle = $this->FontStyle;
		$w = $this->w - $this->lMargin - $this->rMargin;
		$spacer = $this->GetStringWidth(chr(32)) * 4;
		$lmargin = $this->lMargin;
		$rmargin = $this->rMargin;
		$x_start = $this->GetX();
		$page_first = $this->page;
		$current_page = $this->page;
		$page_fill_start = false;
		$page_fill_end = false;
		$current_column = $this->current_column;
		if (TCPDF_STATIC::empty_string($numbersfont)) {
			$numbersfont = $this->default_monospaced_font;
		}
		if (TCPDF_STATIC::empty_string($filler)) {
			$filler = ' ';
		}
		if (TCPDF_STATIC::empty_string($page)) {
			$gap = ' ';
		} else {
			$gap = '';
			if ($page < 1) {
				$page = 1;
			}
		}
		$this->SetFont($numbersfont, $fontstyle, $fontsize);
		$numwidth = $this->GetStringWidth('00000');
		$maxpage = 0; //used for pages on attached documents
		foreach ($this->outlines as $key => $outline) {
			// check for extra pages (used for attachments)
			
			//include only up to level 2 bookmarks in table of contents
			if ($outline['l']== 2) {
				continue;
			} 
			
			
			if (($this->page > $page_first) AND ($outline['p'] >= $this->numpages)) {
				$outline['p'] += ($this->page - $page_first);
			}
			if ($this->rtl) {
				$aligntext = 'R';
				$alignnum = 'L';
			} else {
				$aligntext = 'L';
				$alignnum = 'R';
			}
			if ($outline['l'] == 0) {
				$this->SetFont('rock', $outline['s'], 15);
			} else {
				$this->SetFont('rockl', $outline['s'], 10);
			}
			$this->SetTextColorArray($outline['c']);
			// check for page break
			$this->checkPageBreak(2 * $this->getCellHeight($this->FontSize));
			// set margins and X position
			if (($this->page == $current_page) AND ($this->current_column == $current_column)) {
				$this->lMargin = $lmargin;
				$this->rMargin = $rmargin;
			} else {
				if ($this->current_column != $current_column) {
					if ($this->rtl) {
						$x_start = $this->w - $this->columns[$this->current_column]['x'];
					} else {
						$x_start = $this->columns[$this->current_column]['x'];
					}
				}
				$lmargin = $this->lMargin;
				$rmargin = $this->rMargin;
				$current_page = $this->page;
				$current_column = $this->current_column;
			}
						
			
			$this->SetX($x_start);
			//$indent = ($spacer * $outline['l']);
			$indent = 0;
			
			if ($this->rtl) {
				$this->x -= $indent;
				$this->rMargin = $this->w - $this->x;
			} else {
				$this->x += $indent;
				$this->lMargin = $this->x;
			}
			$link = $this->AddLink();
			$this->SetLink($link, $outline['y'], $outline['p']);
			// write the text
			if ($this->rtl) {
				$txt = ' '.$outline['t'];
			} else {
				$txt = $outline['t'].' ';
			}
									
			if ($outline['l']== 0) {
				$this->Ln(10);
			} 
			
			$this->Write(0, $txt, $link, false, $aligntext, false, 0, false, false, 0, $numwidth, '');
			if ($this->rtl) {
				$tw = $this->x - $this->lMargin;
			} else {
				$tw = $this->w - $this->rMargin - $this->x;
			}
						
			//$this->SetFont('rock', $fontstyle, $fontsize);
						
			if (TCPDF_STATIC::empty_string($page)) {
				$pagenum = $outline['p'];
			} else {
				// placemark to be replaced with the correct number
				$pagenum = '{#'.($outline['p']).'}';
				if ($this->isUnicodeFont()) {
					$pagenum = '{'.$pagenum.'}';
				}
				$maxpage = max($maxpage, $outline['p']);
			}
			$fw = ($tw - $this->GetStringWidth($pagenum.$filler));
			$wfiller = $this->GetStringWidth($filler);
			if ($wfiller > 0) {
				$numfills = floor($fw / $wfiller);
			} else {
				$numfills = 0;
			}
			if ($numfills > 0) {
				$rowfill = str_repeat($filler, $numfills);
			} else {
				$rowfill = '';
			}
			if ($this->rtl) {
				$pagenum = $pagenum.$gap.$rowfill;
			} else {
				$pagenum = $rowfill.$gap.$pagenum;
			}
			// write the number
			
			if ($outline['l'] != 0) {
				$tw += 2;
			} 
			
			$tw -= 3;

			$this->Cell($tw, 0, $pagenum, 0, 1, $alignnum, 0, $link, 0);
		}
		$page_last = $this->getPage();
		$numpages = ($page_last - $page_first + 1);
		// account for booklet mode
		if ($this->booklet) {
			// check if a blank page is required before TOC
			$page_fill_start = ((($page_first % 2) == 0) XOR (($page % 2) == 0));
			$page_fill_end = (!((($numpages % 2) == 0) XOR ($page_fill_start)));
			if ($page_fill_start) {
				// add a page at the end (to be moved before TOC)
				$this->addPage();
				++$page_last;
				++$numpages;
			}
			if ($page_fill_end) {
				// add a page at the end
				$this->addPage();
				++$page_last;
				++$numpages;
			}
		}
		$maxpage = max($maxpage, $page_last);
		
		//offset to subtract to cater for the cover and table of contents 
		$pagenumoffset = ($maxpage-$this->numnormalpages) + 1; //+ 1 for the cover
		
		
		if (!TCPDF_STATIC::empty_string($page)) {
			for ($p = $page_first; $p <= $page_last; ++$p) {
				// get page data
				$temppage = $this->getPageBuffer($p);
				for ($n = 1; $n <= $maxpage; ++$n) {
					// update page numbers
					$a = '{#'.$n.'}';
					// get page number aliases
					$pnalias = $this->getInternalPageNumberAliases($a);
					// calculate replacement number
					if (($n >= $page) AND ($n <= $this->numpages)) {
						$np = $n + $numpages;
					} else {
						$np = $n;
					}
					
					//adjust with offset for the cover ang table of contents pages
					//$na = $na - $pagenumoffset;
					
					$na = TCPDF_STATIC::formatTOCPageNumber(($this->starting_page_number + $np - 1 - $pagenumoffset));
					$nu = TCPDF_FONTS::UTF8ToUTF16BE($na, false, $this->isunicode, $this->CurrentFont);
					
					
					
					
					// replace aliases with numbers
					foreach ($pnalias['u'] as $u) {
						$sfill = str_repeat($filler, max(0, (strlen($u) - strlen($nu.' '))));
																		
						if ($this->rtl) {
							$nr = $nu.TCPDF_FONTS::UTF8ToUTF16BE(' '.$sfill, false, $this->isunicode, $this->CurrentFont);
						} else {
							$nr = TCPDF_FONTS::UTF8ToUTF16BE($sfill.' ', false, $this->isunicode, $this->CurrentFont).$nu;
							
						}
						$temppage = str_replace($u, $nr, $temppage);
					}
					foreach ($pnalias['a'] as $a) {
						$sfill = str_repeat($filler, max(0, (strlen($a) - strlen($na.' '))));
												
						
						if ($this->rtl) {
							$nr = $na.' '.$sfill;
						} else {
							$nr = $sfill.' '.$na;
						}
												
						$temppage = str_replace($a, $nr, $temppage);
						
					}
				}
				// save changes
				$this->setPageBuffer($p, $temppage);
			}
			// move pages
			$this->Bookmark($toc_name, 0, 0, $page_first, $style, $color);
			if ($page_fill_start) {
				$this->movePage($page_last, $page_first);
			}
			for ($i = 0; $i < $numpages; ++$i) {
				$this->movePage($page_last, $page);
			}
		}
	}
	
	public function writeHeader($txt, $orientation = 'P') {
		
		
		$this->AddPage($orientation);		
		$this->Bookmark($txt, 0, 0, '', '', array(93,0,0));
		$this->SetFont('rockb', '', 25, '', true);
		$this->setTextColor(0, 0, 0);
		$this->SetX($this->lMargin-1);
		$this->Cell(0, 0, $txt, 0, 1, 'L');
	}
	
	public function writeSubHeader($txt) {				
		$this->Bookmark($txt, 1, 0, '', '', array(0,0,0));
		$this->SetFont('rock', '', 18, '', true);
		$this->setTextColor(93, 0, 0);
		$this->SetX($this->lMargin-1);
		$this->Cell(0, 5, $txt, 0, 1, 'L');
		$this->SetFont('rockl', '', 10, '', true);
		$this->setTextColor(0, 0, 0);
	}
	
	public function writeH3($txt) {				
		$this->Bookmark($txt, 2, 0, '', '', array(0,0,0));
		$this->SetFont('rock', '', 10, '', true);
		$this->setTextColor(0, 0, 0);
		$this->SetX($this->lMargin-1);
		$this->Cell(0, 5, $txt, 0, 1, 'L');
		$this->SetFont('rockl', '', 10, '', true);
		$this->setTextColor(0, 0, 0);
	}
	
	
} // end of class


Class HTMLTable { //helper class on building tables
	
	public $htmlbody = '';
	public $thhtml ='';
	public $tmprow = '';
	public $rowopenflag = false;
	protected $unit = "$";
	
	
	public $indent = "      ";
	
	
	public function addTH($txt, $w, $align, $font = '', $span = false ){
		$w = ($w == ''?'':'width:'.$w);	
		$font = ($font == ''?'':'font-size:'.$font.'; ');	
		$cspan = (!$span?'':' colspan="'.$span.'"');
		
		$this->thhtml .= '<td style="'.$w.'; background-color:#000; color:#fff; text-align:'.$align.'; border:0px solid #000; font-family: \'arialbd\';'. $font .'" '. $cspan .'>'.$txt.'</td>';	
	}
	public function addTD($txt, $w, $s, $align, $type='normal', $span = false, $font = ''){ //call this after a row is opened
		if (!$this->rowopenflag) return;	
		$bg = ($type=='normal'?'':'background-color:#f1f1f1; ');	
		$cspan = (!$span?'':' colspan="'.$span.'"');
		$w = ($w == ''?'':'width:'.$w.'; ');
		$s = ($s != 'bold'?"font-family: 'arialmt'; ":" font-family:'arialbd'; ");
		$align = ($align != ''? ' align ="'.$align.'" ':'');
		$font = ($font == ''?'':'font-size:'.$font.'; ');
		
		
		$this->tmprow .= '<td style="'.$w.''.$bg.'color:#000; '.$s.' border: none; '.$font.'" '.$cspan.$align.'>'.$txt.'</td>';	
	
	}
	
	public function openRow() {
		$this->rowopenflag = true;
		$this->tmprow = "<tr>";
	}
	
	public function closeRow() {
		$this->rowopenflag = false;
		$this->tmprow .= "</tr>";
		$this->htmlbody .= $this->tmprow;
		$this->tmprow = "";
	}
	
	public function getHTML() {
		$html = '<table cellspacing="0" cellpadding="4" border="0">';
		if ($this->thhtml != "") {
			$html .= "<thead><tr>".$this->thhtml.'</tr></thead>';
		}
		$html .= "<tbody>".$this->htmlbody."</tbody></table>";
		
		return $html;
	
	}
	
	public function addTHRow($columns, $pad = false) {
		$numcol = count($columns);
		
		$w1 = 40;
		$wx = intval(60/($numcol-1));			
		$wl = 60 - ($wx*($numcol-2));
		
		//fix wl
		if ($wl != $wx) {
			$w1 = $w1 + ($wl - $wx);
			$wl = $wx;
		}
		
		foreach($columns as $key=>$column) {
			$w = ($key<$numcol-1 && $key > 0? $wx: ($key==0? $w1: $wl));	
			$u = htmlentities($this->unit, ENT_COMPAT,'ISO8859-15');
			$column = htmlentities($column, ENT_COMPAT,'ISO8859-15');
			//$u = (strrpos($column, $u) == false? $u . $column: $column );	
			$align = ($key!=0?'right':'left');
			
						
			if ($key != 0 )	{			
				$this->addTH($column, $w.'%', $align, '',false);			
			} else {
				if ($pad) {
					$this->addTH('', '2%', $align, '',false);
					$w -= 2;
					$this->addTH($column, $w.'%', $align, '',false);
				} else {
					$this->addTH($column, $w.'%', $align, '',2);
				}	
				
			}
			
			
		}
	
	}
	
	public function setUnit($u) {
		$this->unit = htmlentities($u, ENT_COMPAT,'ISO8859-15');
	}
	
	
	public function addTDRow($columns, $f=null, $pad = false) {
		$numcol = count($columns);
				
		$w1 = 40;
		$wx = intval(60/($numcol-1));			
		$wl = 60 - ($wx*($numcol-2));
		
		//fix wl
		if ($wl != $wx) {
			$w1 = $w1 + ($wl - $wx);
			$wl = $wx;
		}
		
		
		
		$this->openRow();
		
		
		$t = "normal";
		$s = "";
		
		if(isset($f)) {
			$t = $f['t'];
			$s = $f['s'];
			
		}
				
		foreach($columns as $key=>$column) {
			$w = ($key<$numcol-1 && $key > 0? $wx: ($key==0? $w1: $wl));	
			$u = htmlentities($this->unit, ENT_COMPAT,'ISO8859-15');
			//$column = htmlentities($column, ENT_COMPAT,'ISO8859-15');
			//$column = (strrpos($column, $u) == false && $key != 0? $u . $column: $column );
						
			
			$align = ($key!=0?'right':'');
			//$this->addTD($column, $w, $s, $align, $t, $span);	

			if ($key != 0 )	{			
				$this->addTD($column, $w.'%', $s, $align, $t, false);			
			} else {
				if ($pad) {
					$this->addTD('', '2%', $s, $align, $t, false);	
					$w -= 2; 		
					$this->addTD($column, $w.'%', $s, $align, $t, false);
				} else {			
					$this->addTD($column, $w.'%', $s, $align, $t, 2);
				}
			}


			
		}
	
		$this->closeRow();
	
	}
	
	public function add1ColRow($txt,$s="",$l='left',$t="normal" ,$span=1) {
		
		$this->openRow();
		$this->addTD($txt,'',$s, $l, $t,intval($span) + 1);
		$this->closeRow();
		
	}
	
	public function add1LColRow($txt,$s="",$l='left',$t="normal" ,$span=1) {
		
		$this->openRow();
		$this->addTD($txt,'',$s, $l, $t,intval($span) + 1, 9);
		$this->closeRow();
		
	}
	
	
	public function addLTHRow($columns, $pad=false) {
		$numcol = count($columns);
		
		$w1 = 14;
		$wx = intval(86/($numcol-1));			
		$wl = 86 - ($wx*($numcol-2));
		
		//fix wl
		if ($wl != $wx) {
			$w1 = $w1 + ($wl - $wx);
			$wl = $wx;
		}
				
		
		
		foreach($columns as $key=>$column) {
			$w = ($key<$numcol-1 && $key > 0? $wx: ($key==0? $w1: $wl));	
			$u = htmlentities($this->unit, ENT_COMPAT,'ISO8859-15');
			//$column = htmlentities($column, ENT_COMPAT,'ISO8859-15');
			//$u = (strrpos($column, $u) == false? $u . $column: $column );	
			$align = ($key!=0?'right':'left');
			//$this->addTH($column, $w, $align,'9' );	
						
			if ($key != 0 )	{			
				$this->addTH($column, $w. '%', $align, 9, false);			
			} else {
				if ($pad) {
					$this->addTH('', '2%', $align, 9, false);
					$w = $w - 2;			
					$this->addTH($column, $w. '%', $align, 9, false);
				} else {		
					$this->addTH($column, $w. '%', $align, 9, 2);
				}
			}		


			
		}
	
	}
	
	public function addLTDRow($columns, $f=null, $pad=false) {
		$numcol = count($columns);
		
		$w1 = 14;
		$wx = intval(86/($numcol-1));			
		$wl = 86 - ($wx*($numcol-2));
		
		//fix wl
		if ($wl != $wx) {
			$w1 = $w1 + ($wl - $wx);
			$wl = $wx;
		}
		
		$this->openRow();
		
		
		$t = "normal";
		$s = "";
		
		if(isset($f)) {
			$t = $f['t'];
			$s = $f['s'];
			
		}
		
		$font = ($t!='total'?'8':'9');
		
		foreach($columns as $key=>$column) {
			$w = ($key<$numcol-1 && $key > 0? $wx: ($key==0? $w1: $wl));	
			$u = htmlentities($this->unit, ENT_COMPAT,'ISO8859-15');
			//$column = htmlentities($column, ENT_COMPAT,'ISO8859-15');
			//$column = (strrpos($column, $u) == false && $key != 0? $u . $column: $column );
			$align = ($key!=0?'right':'');
			//$this->addTD($column, $w, $s, $align, $t, false, $font);

			if ($key != 0 )	{			
				$this->addTD($column, $w.'%', $s, $align, $t, false, $font);			
			} else {
				if ($pad) {
					$this->addTD('', '2%', $s, $align, $t, false, $font);
					$w -= 2;
					$this->addTD($column, $w.'%', $s, $align, $t, false, $font);
				} else{			
					$this->addTD($column, $w.'%', $s, $align, $t, 2, $font);
				}	
			}	


			
		}
	
		$this->closeRow();
	
	}
	
	
	
}


Class PDFHandler {

	protected $pdf;

	public function __construct($title = 'Biz Plan') {

		// create new PDF document
		 $this->pdf = new TOC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215.9,279.4), true, 'UTF-8', false);
		 $this->pdf->plantitle = $title;

		// set document information
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('PJJA');
		$this->pdf->SetTitle($this->pdf->plantitle);
		$this->pdf->SetSubject('Business Plan');
		$this->pdf->SetKeywords('PDF, Business Plan');
		
		$this->pdf->SetMargins(PDF_MARGIN_LEFT*1.2, PDF_MARGIN_TOP*1.4, PDF_MARGIN_LEFT*1.2);
		$this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$this->pdf->setLanguageArray($l);
		}
		
		// set default font subsetting mode
		$this->pdf->setFontSubsetting(true);

		// Set font
		$this->pdf->SetFont('fradmcn', '', 12, '', true);
		$this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	}

	
	public function build() {
		$this->buildCover();
		$this->buildExecSummary();
		$this->buildCompany();
		$this->buildProdServices();
		$this->buildTargetMarket();
		$this->buildStratImplementation();
		$this->buildFinancePlan();
		$this->buildFinanceStatement();
		$this->buildIndex();
		$this->buildDummyChapters();
		$this->buildTOC();
		
	}
	

	public function buildCover($contact = null) {
		$bp_name = $_SESSION['bpName'];
		$prepared = date('F Y');
	
		//do not print header and footer in cover page
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);
		
		$this->pdf->AddPage();
		
		//pagebreak off to expand images
		$this->pdf->SetAutoPageBreak(false,0);
		
		$this->pdf->Image(PDF_IMAGES_PATH.'cover-bg2.jpg', 0, 0, 215.9,279.4, 'JPEG',null ,null ,2);
	
		// Set some content to print
		$html = 'CONFIDENTIAL MESSAGE';

		$this->pdf->setTextColor(255, 204, 51);
		$this->pdf->MultiCell(155, 5, $html, 0, 'L', 0, 0, '', 96.2, true);

		$this->pdf->SetFont('rockb', '', 50, '', true);
		$this->pdf->MultiCell(155, 20, strtoupper($bp_name), 0, 'L', 0, 0, '', 102.2, true);
		//$this->pdf->MultiCell(155, 20, 'COMPANY', 0, 'L', 0, 0, '', 122, true);

		//$this->pdf->SetFont('fradmcn', '', 12, '', true);
		//$this->pdf->MultiCell(155, 5, 'THE NEW FORCE IN ACCOUNTANCY PRACTICE', 0, 'L', 0, 0, '', 145, true);

		$this->pdf->SetFont('fradmcn', '', 20, '', true);
		$this->pdf->setTextColor(0, 0, 0);
		$this->pdf->MultiCell(155, 5, 'BUSINESS PLAN', 0, 'L', 0, 0, '', 160, true);

		$this->pdf->SetFont('rock', '', 10, '', true);
		$this->pdf->MultiCell(155, 5, "Prepared {$prepared}", 0, 'L', 0, 0, '', 168, true);

		$this->pdf->SetFont('fradmcn', '', 15, '', true);
		$this->pdf->setTextColor(204, 0, 0);

		$this->pdf->MultiCell(155, 5, 'CONTACT INFORMATION', 0, 'L', 0, 0, '', 240, true);

		$this->pdf->SetFont('fradmcn', '', 10, '', true);
		$this->pdf->setTextColor(255, 204, 51);
		$this->pdf->MultiCell(0, 5, $contact['user_name'], 0, 'L', 0, 0, '', 248, true);
		if (isset($contact['user_address'])) {
			$this->pdf->MultiCell(0, 5, $contact['user_address'], 0, 'R', 0, 0, '', 248, true);
		}

		$this->pdf->MultiCell(0, 5, $contact['user_email'], 0, 'L', 0, 0, '', 252, true);
		if (isset($contact['user_phone'])) {
			$this->pdf->MultiCell(0, 5, $contact['user_phone'], 0, 'R', 0, 0, '', 252, true);
		}

		if (isset($contact['user_website'])) {
			$this->pdf->MultiCell(0, 5, $contact['user_website'], 0, 'L', 0, 0, '', 256, true);
		}
	
		//reset true to include header and footer for succeeding pages
		$this->pdf->setPrintHeader(true);
		$this->pdf->setPrintFooter(true);
		
		$this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	}
	
	
	// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 059', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font




// set auto page breaks


// create some content ...
	

	public function buildExecSummary($chapterdata=null) {
		
		$this->pdf->writeHeader('Executive Summary');
		$this->pdf->writeSubHeader('Who We Are');
		$html = '<p style="line-height:1.5">Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent'
		.'elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos'
		.'honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.'
		.'Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.</p>'
		.'<p style="line-height:1.5">Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent'
		.'elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos'
		.'honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.'
		.'Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.</p>'
		.'<p style="line-height:1.5">Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent'
		.'elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos'
		.'honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.'
		.'Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.</p>';

		$this->pdf->writeHTML($html, true, false, false, false, 'L');
		$this->pdf->Ln(4);
		$this->pdf->writeSubHeader('What We Sell');
		
		$html = <<<'EOT'
		<p style="line-height:1.5">Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent'
		elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos
		honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.
		Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.</p>
		<p style="line-height:1.5">Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent
		elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos
		honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.
		Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.</p>
		<p style="line-height:1.5">Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent
		elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos
		honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.
		Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.</p>
		<p style="line-height:1.5">Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent
		elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos
		honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.
		Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.Lorem ipsum dolor
		sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent elaboraret eu pro. Nisl
		consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos honestatis qui an, no vim mundi
		accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel. Cu eos quidam utroque
		deleniti, justo facer in mel, probatus antiopam imperdiet ut has.Lorem ipsum dolor sit amet, nec cu idque
		graece melius. Possim delicata praesent ei eam, praesent elaboraret eu pro. Nisl consequat consectetuer
		ex quo, iudico intellegat vim ne. Nullam torquatos honestatis qui an, no vim mundi accusam ullamcorper.
		Cu est dolor ignota nominavi, cu augue detracto vel. Cu eos quidam utroque deleniti, justo facer in mel,
		probatus antiopam imperdiet ut has.</p>
		<p style="line-height:1.5">Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent
		elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos
		honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.
		Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.
		Lorem ipsum dolor sit amet, nec cu idque graece melius. Possim delicata praesent ei eam, praesent
		elaboraret eu pro. Nisl consequat consectetuer ex quo, iudico intellegat vim ne. Nullam torquatos
		honestatis qui an, no vim mundi accusam ullamcorper. Cu est dolor ignota nominavi, cu augue detracto vel.
		Cu eos quidam utroque deleniti, justo facer in mel, probatus antiopam imperdiet ut has.</p>				
EOT;
	
		$this->pdf->writeHTML($html, true, false, false, false, 'L');
	
		
	}
		
		public function buildCompany($contentdata=null) {
		
		}
		

		public function buildProdServices($contentdata = null) {
		
		}
		
	public function buildTargetMarket($contentdata=null){
		
	}
		
		public function buildStratImplementation($contentdata=null) {
		
		}

		
		public function buildFinancePlan($contentdata=null) {
		
			$this->pdf->writeHeader('Financial Plan');
			$this->pdf->writeSubHeader('Revenue Forecast');

			$this->pdf->Ln(1);
			$this->pdf->writeH3('Revenue Forecast Table');


			$this->pdf->Ln(3);
			$thtml = new HTMLTable();

			$thtml->addTHRow(array(' ','FY2013','FY2014','FY2015'));



			$thtml->add1ColRow('Revenue','bold', 'left', 'normal','4');
			$thtml->addTDRow(array('Revenue Stream 1','£224,687','£46,000','£5,520'), null, true);
			$thtml->addTDRow(array('Revenue Stream 2','£224,687','£46,000','£5,520'), null, true);
			$thtml->addTDRow(array('Fees','£224,687','£46,000','£5,520'), null, true);

			// add total row with bg
			$thtml->addTDRow(array('Total Revenue','£224,687','£46,000','£5,520'), array('t'=>'total','s'=>'bold'));

			$thtml->add1ColRow('Direct Cost','bold', 'left', 'normal','4');

			$thtml->addTDRow(array('Revenue Stream 1','£224,687','£46,000','£5,520'), null, true);
			$thtml->addTDRow(array('Revenue Stream 2','£224,687','£46,000','£5,520'), null, true);
			$thtml->addTDRow(array('Fees','£224,687','£46,000','£5,520'), null, true);

			// add total row with bg
			$thtml->addTDRow(array('Total Direct Cost','£224,687','£46,000','£5,520'), array('t'=>'total','s'=>'bold'));

			$thtml->addTDRow(array('Gross Margin','£224,687','£46,000','£5,520'));

			$thtml->addTDRow(array('Gross Margin %','75%','76%','66%'), array('t'=>'total','s'=>'bold'));



			$this->pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');


			//$pdf->write('',$thtml->getHTML(),'', false, 'L', true,0, false, false, 0, 0, '');

			//echo  $thtml->getHTML();
		
		}


		public function buildFinanceStatement($contentdata=null) {		
			
		
		}
				
		public function buildIndex($contentdata=null) {		
			
			$this->pdf->writeHeader('Appendix', 'L');
			$this->pdf->writeSubHeader('Revenue Forecast');

			$this->pdf->Ln(1);
			$this->pdf->writeH3('Revenue Forecast Table (With Monthly Detail)');


			$this->pdf->SetFont('arialbd', '', 11, '', true);
			$this->pdf->Ln(3);

			$thtml = new HTMLTable();

			//13 columns
			$thtml->addLTHRow(array("FY2013", "Aug '12", "Sep '12", "Oct '12", "Nov '12", "Dec '12", "Jan '13", "Feb '13", "Mar '13", "Apr '13", "May '13", "Jun '13", "Jul '13"));
			$thtml->add1LColRow('Revenue','bold', 'left', 'normal','13');

			$thtml->addLTDRow(array('Revenue Stream 1','£0', '£46,000', '£5,520', '£161', '£11,500', '£46', '£69,000', '£0', '£46,000','£230', '£46,000', '£230'), null, true);
			$thtml->addLTDRow(array('Revenue Stream 2','£0', '£0', '£0', '£0', '£0', '£0', '£0', '£0', '£0', '£0', '£14,400', '£24,000'), null, true);
			$thtml->addLTDRow(array('Fees','£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50'), null, true);



			// add total row with bg
			$thtml->addLTDRow(array('Total Revenue', '£50', '£46,050', '£5,570', '£211', '£11,550', '£96', '£69,050', '£50', '£46,050', '£280', '£60,450', '£24,280'), array('t'=>'total','s'=>'bold'));

			$thtml->add1LColRow('Direct Cost','bold', 'left', 'normal','13');

			$thtml->addLTDRow(array('Revenue Stream 1 with Revenue Stream Extra','£0', '£46,000', '£5,520', '£161', '£11,500', '£46', '£69,000', '£0', '£46,000','£230', '£46,000', '£230'), null, true);
			$thtml->addLTDRow(array('Revenue Stream 2','£0', '£0', '£0', '£0', '£0', '£0', '£0', '£0', '£0', '£0', '£14,400', '£24,000'), null, true);
			$thtml->addLTDRow(array('Fees','£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50', '£50'), null, true);

			// add total row with bg
			$thtml->addLTDRow(array('Total Direct Cost','£50', '£46,050', '£5,570', '£211', '£11,550', '£96', '£69,050', '£50', '£46,050', '£280', '£60,450', '£24,280'), array('t'=>'total','s'=>'bold'));

			$thtml->addLTDRow(array('Gross Margin','£50', '£46,050', '£5,570', '£211', '£11,550', '£96', '£69,050', '£50', '£46,050', '£280', '£60,450', '£24,280'));

			$thtml->addLTDRow(array('Gross Margin %','50%', '78%', '78%', '72%', '78%', '64%', '78%', '50%', '78%', '73%', '73%', '59%'), array('t'=>'total','s'=>'bold'));

			$this->pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
			//echo  $thtml->getHTML();
		}

		public function buildDummyChapters() {
			// set a bookmark for the current position
			$l1color = array(93,0,0);
			$l2color = array(0,0,0);
			$l1style = ""; //'B', empty for regular
			$l2style = ""; //'B', empty for regular



			$this->pdf->AddPage('P');
			$this->pdf->Bookmark('Chapter 1', 0, 0, '', $l1style, $l1color);

			// print a line using Cell()
			$this->pdf->Cell(0, 10, 'Chapter 1', 0, 1, 'L');

			$this->pdf->AddPage();
			$this->pdf->Bookmark('Paragraph 1.1', 1, 0, '', $l2style, $l2color);
			$this->pdf->Cell(0, 10, 'Paragraph 1.1', 0, 1, 'L');

			$this->pdf->AddPage();
			$this->pdf->Bookmark('Paragraph 1.2', 1, 0, '', $l2style, $l2color);
			$this->pdf->Cell(0, 10, 'Paragraph 1.2', 0, 1, 'L');

			$this->pdf->AddPage();
			$this->pdf->Bookmark('Sub-Paragraph 1.2.1', 2, 0, '', 'I', array(0,128,0));
			$this->pdf->Cell(0, 10, 'Sub-Paragraph 1.2.1', 0, 1, 'L');

			$this->pdf->AddPage();
			$this->pdf->Bookmark('Paragraph 1.3', 1, 0, '', $l2style, $l2color);
			$this->pdf->Cell(0, 10, 'Paragraph 1.3', 0, 1, 'L');

			// add some pages and bookmarks
			for ($i = 2; $i < 6; $i++) {
				$this->pdf->AddPage();
				$this->pdf->Bookmark('Chapter '.$i, 0, 0, '', $l1style, $l1color);
				$this->pdf->Cell(0, 10, 'Chapter '.$i, 0, 1, 'L');
			}
				

				$this->pdf->AddPage('L');
				$this->pdf->Bookmark('Chapter '.$i, 0, 0, '', $l1style, $l1color);
				$this->pdf->Cell(0, 10, 'Chapter '.$i, 0, 1, 'L');

				
			// . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
		
		}

		public function buildTOC() { //must be called after all pages were created
			//mark number of normal pages
			$this->pdf->numnormalpages = $this->pdf->getNumPages();


			// add a new page for TOC
			$this->pdf->addTOCPage('P');

			// write the TOC title and/or other elements on the TOC page
			$this->pdf->SetFont('rock', 'b', 25);
			$this->pdf->setTextColor(0, 0, 0);
			$this->pdf->Ln();
			$this->pdf->MultiCell(0, 0, 'Table Of Contents', 0, 'L', 0, 1, '', '', true, 0);
			$this->pdf->SetFont('rock', '', 15);	
			$this->pdf->addTOC(2, 'rock', '.','Table of Contents', '', array(128,0,0));
			// end of TOC page
			$this->pdf->endTOCPage();

		}

		public function output ($title) {
			// Close and output PDF document
			// This method has several options, check the source code documentation for more information.
			$this->pdf->Output('bizplanpdf1.pdf', 'I');
		
		}
		
}

//$pdf = new PDFHandler('Biz Plan 123');
//$pdf->build();
//$pdf->output('plan');


//============================================================+
// END OF FILE
//============================================================+
