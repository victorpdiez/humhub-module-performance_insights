var performace_history = {

	init: function(baseUrl){
		this.cacheDom();
		this.bindUIActions();
		this.initDatePicker();
		this.baseUrl=baseUrl;
	},

	cacheDom:function(){        
		this.datePickerInput=$('.input-datepicker');
		this.performanceGrid=$('.grid-view');
		this.gridBulkAction=$('#performance-action-button');
		this.flashMsg=$('.flash-msg');	
		this.pjaxGridView=$('#pjax-grid-view')	
	},
	bindUIActions: function(){
		this.gridBulkAction.on('click', this.handleGridActionClick);		
	},
	initDatePicker:function(){
		this.datePickerInput.datepicker({
			format: 'mm/dd/yyyy',
		});
	},
	handleGridActionClick:function(){		
		performace_history.checkBoxId=$('.grid-view').yiiGridView('getSelectedRows'); 	
		if(performace_history.checkBoxId.length){
			performace_history.sendHttpDeleteRequest(this);
		} 
	},
	sendHttpDeleteRequest:function(ele){
		$.ajax({
			type:'POST',
			url:this.baseUrl+'performance_insights/admin/delete-selected',          
			data:{checkBoxId:this.checkBoxId},
			dataType: "json",
			success: function( data, textStatus, jQxhr ){
				$.pjax({container: '#pjax-grid-view'});
				performace_history.flashMsg.addClass('alert-'+data.outcome);
				performace_history.flashMsg.find('span').html(data.message);
				$(ele).attr('disabled',false);    
			},
			error: function( jqXhr, textStatus, errorThrown ){

			},
			beforeSend: function() {
				$(ele).attr('disabled',true);   
			},
		});
	},
	

};