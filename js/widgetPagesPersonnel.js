
Ext.ns('bpo.widgetPage');bpo.widgetPage.personnel={page_title:"Personnel Table",init:function(title){if(title!==undefined){this.page_title=title;}
this.initTabs();$j('.expense-item.not-selected').live('hover',function(){$j(this).toggleClass('hover');});$j('.expense-item.not-selected').live('click',function(){$j(this).prev('a').click();});$j('.continue').live('click',function(){bpo.scrollToTop();$j('.nav').data('tabs').next();});


$j('.add-an-expense').live('click',function(){
		bpo.message.prompt({
		title:"New Hire".l(),
		prompt:"What do you want to call this hire?".l(),
		confirmText:'Add New Hire'.l(),
		maxLength:255
		},
		"New Hire".l(),function(prompt){
			bpo.dimAjaxArea('dim-action-expense-budget-list');
			window.addNewPerson(prompt);});
		});

$j('a.radio-link').live('click',function(){
	var cb=$j(this).find('input');
	var fn=function(){
		cb.attr('checked','checked');};
		setTimeout(fn,1);
});
	
$j('.show-preview:not(.disabled)').live('click',this.showPreview);
$j('#reorder-expenses:not(.disabled)').live('click',this.reorderExpenses);},
	initTabs:function(){
		var pg = getParamByName('pgIndex'); pg = ( pg == "" ? 0 : parseInt( pg ) );
		$j('.nav').tabs('div.pages > div.page',{
			tabs:'a',current:'active',initialIndex:pg});},
				initEditor:function(){Ext.select(".item-header .remove").on('click',function(e,el){
					var uuid=Ext.fly(el).parent('.expense-budget-edit').getAttribute('rel');
					bpo.message.confirm(false,"Are you sure you want to remove this hire?".l(),function(id){
							if(id==='yes'){window.deleteSelectedPerson();bpo.dimAjaxArea('dim-action-expense-budget-list');}});});},
	reorderExpenses:function(){var newId=Ext.id();var reorderEl=$j('#expense-budget-reorder').clone().attr('id',newId).appendTo(document.body);
	var overlay=new Ext.ux.Overlay({id:'reorder',
	contentEl:newId,height:'auto',width:1600,
	autoScroll:true,title:'Reorder Personnel'.l(),
	buttons:[{xtype:'aquabutton',color:'primary',text:"I'm Done",handler:function(){overlay.destroy();}}]});overlay.show();reorderEl.find('ul').sortable({cursor:'move',placeholder:'placeholder',tolerance:'pointer',scrollSensitivity:40,update:function(event,ui){var uuids=reorderEl.find('li').map(function(){return this.getAttribute('rel');});window.personnel.setPersonnelOrder($j.makeArray(uuids),function(){bpo.setSortableItemBusy(ui.item);window.reRenderContexts();});}});reorderEl.find('ul').disableSelection();},showPreview:function(){var previewEl=Ext.get('preview-table');previewEl.child('div').update();var previewOverlay=Ext.WindowMgr.get('preview')||new bpo.component.PreviewOverlay({id:'preview',title:"Preview:  "+bpo.widgetPage.personnel.page_title,contentEl:previewEl});previewOverlay.show();window.reRenderPreview();}};if(typeof this['dwr']=='undefined')this.dwr={};if(typeof dwr['engine']=='undefined')dwr.engine={};if(typeof dwr.engine['_mappedClasses']=='undefined')dwr.engine._mappedClasses={};if(typeof this['dwr']=='undefined')this.dwr={};if(typeof dwr['engine']=='undefined')dwr.engine={};if(typeof dwr.engine['_mappedClasses']=='undefined')dwr.engine._mappedClasses={};if(window['dojo'])dojo.provide('dwr.interface.personnel');if(typeof this['personnel']=='undefined')personnel={};personnel._path=''+JAWR.jawr_dwr_path+'';personnel.init=function(callback){return dwr.engine._execute(personnel._path,'personnel','init',arguments);};personnel.setValues=function(p0,p1,p2,p3,callback){return dwr.engine._execute(personnel._path,'personnel','setValues',arguments);};personnel.setValues=function(p0,p1,p2,p3,callback){return dwr.engine._execute(personnel._path,'personnel','setValues',arguments);};personnel.isCleanSlate=function(callback){return dwr.engine._execute(personnel._path,'personnel','isCleanSlate',arguments);};personnel.setPersonnelOrder=function(p0,callback){return dwr.engine._execute(personnel._path,'personnel','setPersonnelOrder',arguments);};personnel.addNewPerson=function(callback){return dwr.engine._execute(personnel._path,'personnel','addNewPerson',arguments);};personnel.addNewPerson=function(p0,callback){return dwr.engine._execute(personnel._path,'personnel','addNewPerson',arguments);};personnel.deletePerson=function(p0,callback){return dwr.engine._execute(personnel._path,'personnel','deletePerson',arguments);};personnel.renamePerson=function(p0,p1,callback){return dwr.engine._execute(personnel._path,'personnel','renamePerson',arguments);};personnel.getSelectedPerson=function(callback){return dwr.engine._execute(personnel._path,'personnel','getSelectedPerson',arguments);};personnel.setSelectedPerson=function(p0,callback){return dwr.engine._execute(personnel._path,'personnel','setSelectedPerson',arguments);};personnel.setSelectedPerson=function(p0,callback){return dwr.engine._execute(personnel._path,'personnel','setSelectedPerson',arguments);};
function getParamByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};
