(function (){

	var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button_purple'); //Add button selector
    var wrapper = $('.box-danger'); //Input field wrapper
    var fieldHTML = '<div class="box-body dynamic_scrape_json"><div class="row"><div class="col-xs-2"><input type="text" class="form-control" placeholder="Title" name="title[]" required></div><div class="col-xs-2"><input type="text" class="form-control" placeholder="Target Element" name="element[]" required></div><div class="col-xs-1"><input type="text" class="form-control" placeholder="Attribute" name="attribute[]" required></div><div class="col-xs-2"><input type="text" class="form-control" placeholder="Positions" name="positions[]"></div><div class="col-xs-4"><input type="text" class="form-control" placeholder="Filters" name="filters[]"></div><div class="col-xs-1"><a href="javascript:void(0);" class="btn btn-danger remove_button_warning"><i class="fa fa-close"></i></a></div></div></div>'; //New input field html 
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

    $('.pagerselector').on("change",function() {
        var x = $('.pagerselector:checked').val();
        if(x == 'replace'){
            $('#replace_with_string').show();
        }
        else{
            $('#replace_with_string').hide();
        }
    });
    
}());