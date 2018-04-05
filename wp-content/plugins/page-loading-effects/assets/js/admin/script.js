/**
 * Script for Admin
 *
 */ 


jQuery(document).ready(function($){
    $('.ple-color-field').wpColorPicker();

    // Only run this if certain class exist
    if(!$("#ple-preloader-setting-page .form-table-3").length<1){  
    	var currentVal = $( '.ple_option_3' ).val();

    	if (currentVal.trim()) {
    		// Preview
	    	$('#ple-preview').addClass('ple-effect'+currentVal);	

	    	// Default Table in the Settings
		    if(currentVal==4){
		    	$(".form-table-3").show();
		    }else{
		    	$(".form-table-3").hide();
		    }
		  	var storedVal = currentVal;
		    $( ".ple_option_3" ).change(function() {
		    	
		 		var getVal = $( this ).val();
		 		
		 		// Preview
		 		$('#ple-preview').addClass('ple-effect'+getVal);
		 		$('#ple-preview').removeClass('ple-effect'+storedVal);

		 		// Custom Fields	
		    	if(getVal==4){
		    		$(".form-table-3").show();
		    		$("#ple-preview").hide();
		    	}else{
		    		$(".form-table-3").hide();
		    		$("#ple-preview").show();
		    	}
		    	
		    	storedVal = getVal;
			});
		}
	}
});

