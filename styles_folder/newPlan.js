
$j(function(){bpo.message.richMessageAsSkirtError();});bpo.plan.financialsOverlay=function(confirmTitle,confirmContent,handle){var workingTitle="Updating financials".l();var workingContent="Please wait just a moment.".l();var overlay=new Ext.ux.Overlay({closable:false,title:confirmTitle,width:480,height:'auto',items:[{html:confirmContent,border:false}],buttons:[{'xtype':'aquabutton','text':'Cancel'.l(),'color':'text','id':'no'},'or'.l(),{'xtype':'aquabutton','text':'Confirm'.l(),'color':'primary','id':'yes'}]});return{show:function(){overlay.buttons[0].handler=function(){overlay.destroy();};overlay.buttons[2].handler=handle;overlay.show();},working:function(){overlay.setTitle(workingTitle);overlay.removeAll();overlay.doLayout();overlay.add([{html:"<div class=\"spinner\"><p>"+workingContent+"</p></div>",border:false}]);overlay.buttons[0].setVisible(false);overlay.buttons[1].setVisible(false);overlay.buttons[2].setVisible(false);overlay.doLayout();},complete:function(){overlay.destroy();}};};