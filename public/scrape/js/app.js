(function (){

	var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button_purple'); //Add button selector
    var wrapper = $('.box-danger'); //Input field wrapper
    var fieldHTML = '<div class="box-body dynamic_scrape_json"><div class="row"><div class="col-xs-2"><input type="text" class="form-control" placeholder="Title" name="title[]"></div><div class="col-xs-2"><input type="text" class="form-control" placeholder="Target Element" name="element[]"></div><div class="col-xs-3"><input type="text" class="form-control" placeholder="Attribute" name="attribute[]"></div><div class="col-xs-4"><input type="text" class="form-control" placeholder="Filters" name="filters[]"></div><div class="col-xs-1"><a href="javascript:void(0);" class="btn btn-danger remove_button_warning"><i class="fa fa-close"></i></a></div></div></div>'; //New input field html 
    var x = 1; //Initial field counter is 1

    $(addButton).click(function(){ //Once add button is clicked
        if(x < maxField){ //Check maximum number of input fields
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); // Add field html
        }
    });
    $(wrapper).on('click', '.remove_button_warning', function(e){ //Once remove button is clicked
        
        e.preventDefault();
        //console.log($(this).parentsUntil('.row'));
        $(this).closest('.dynamic_scrape_json').remove(); //Remove field html
        x--; //Decrement field counter
    });

	$('.singlepageselector').on("change",function() {
    	var singlepage = $('.singlepageselector:checked').val();
    	if(singlepage == 'single'){
    		$('#page_string').hide();
    	}
    	else{
    		$('#page_string').show();
    	}
	});
    
}());

(function (){

    var maxFilterField = 10; //Input fields increment limitation
    var addFilterButton = $('.add_field_button'); //Add button selector
    var filterWrapper = $('.box-danger'); //Input field wrapper
    var fieldFilterHTML = '<div class="box-body dynamic_scrape_json"><div class="row"><div class="col-xs-2"><input type="text" class="form-control" placeholder="Title" name="title[]"></div><div class="col-xs-2"><input type="text" class="form-control" placeholder="Target Element" name="element[]"></div><div class="col-xs-3"><input type="text" class="form-control" placeholder="Attribute" name="attribute[]"></div><div class="col-xs-4"><input type="text" class="form-control" placeholder="Filters" name="filters[]"></div><div class="col-xs-1"><a href="javascript:void(0);" class="btn btn-danger remove_button_warning"><i class="fa fa-close"></i></a></div></div></div>'; //New input field html 
    var y = 1; //Initial field counter is 1

    $(addButton).click(function(){ //Once add button is clicked
        if(y < maxFilterField){ //Check maximum number of input fields
            y++; //Increment field counter
            $(filterWrapper).append(fieldFilterHTML); // Add field html
        }
    });
    $(filterWrapper).on('click', '.remove_filter_button_warning', function(e){ //Once remove button is clicked
        
        e.preventDefault();
        //console.log($(this).parentsUntil('.row'));
        $(this).closest('.dynamic_filter_scrape_json').remove(); //Remove field html
        y--; //Decrement field counter
    });
    
}());