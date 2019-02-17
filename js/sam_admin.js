
$(function() {

	// ======================================================= blacklist features start ===============================================================
	$(document).on("click","a.remove-from-blacklist",function(event){
		event.preventDefault();

			
		var row = $(this).closest( "tr" );
		var ip = row.find('.blacklist-ip').text();	
		
		var confirmMessage = $('table.fixed_headers').attr( 'remove-confirm-message' ) +  " " + ip + " ?";
		
		remove_from_blacklist_yes.row = row;
		remove_from_blacklist_yes.ip = ip;
		showModalYesNoDialog("", confirmMessage, remove_from_blacklist_yes, remove_from_blacklist_no);
				
	});
	
	
	function remove_from_blacklist_no(){
	
	}
	
	
	function remove_from_blacklist_yes(){
	
		var row = remove_from_blacklist_yes.row;
		var ip = remove_from_blacklist_yes.ip;	
	
		var ajaxErrorMessage = $('.fixed_headers').attr('ajax-error-message');
		var ajaxurl = $('.fixed_headers').attr('url');
		
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'ip': ip,
				action: 'sam_remove_ip_from_blacklist'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				showModalOkDialog(ajaxErrorMessage, response);

			},
			success: function(response){
					console.log( response.trim() );
					if (response.trim() == 1){
						row.addClass('hidden');
					}
					else{
						showModalOkDialog(ajaxErrorMessage, response);
					}
					
			}
		
		});	
	}
	
	





	$(document).on("click","button.add_ip_to_blacklist",function(event){
		event.preventDefault();

		var ip = $('input.input_ip_to_blacklist').val();
		var ajaxurl = $('.fixed_headers').attr('url');
		var ajaxErrorMessage = $('.fixed_headers').attr('ajax-error-message');
		
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'ip': ip,
				action: 'sam_add_ip_to_blacklist'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				alert('f');
				showModalOkDialog( ajaxErrorMessage, response);

			},
			success: function(response){
					console.log( response.trim() );
					
					var vars = response.trim().split('|');
					var res = vars[0];
					var value = vars[1];
					
					if (res == 1){
						// append added IP to the blacklist
						var removeWord  = $('table.fixed_headers').attr( 'remove-word' );
						var v = $( "<tr> <td class=\"blacklist-ip\">" + value + "</td> <td class=\"command-column\"><a class=\"remove-from-blacklist\" href=\"#\">" + removeWord + "</a></td> </tr>");
						$( "table .fixed_headers" ).append( v );
						// clear entry
						$('input.input_ip_to_blacklist').val("");
					}
					else if (res == 0){
						var errorMessage = $('.fixed_headers').attr('ajax-error-bad-ip-message');
						showModalOkDialog(errorMessage, value);
					}
					else if (res == 2){
						var doubleIPErrorMessage = $('.fixed_headers').attr('ajax-error-double-ip-message');
						showModalOkDialog(doubleIPErrorMessage, value);
					}

					
			}
		
		});
			
			
			
	});



	// clear blacklist
	$(document).on("click","button.clear_blacklist", function(event){
		event.preventDefault();
		
		var confirmMessage = $('table.fixed_headers').attr( 'clear-confirm-message' );
		
		showModalYesNoDialog("", confirmMessage, clear_blacklist_yes, function(){});
				
	});

	function clear_blacklist_yes(){
		var ajaxErrorMessage = $('.fixed_headers').attr('ajax-error-message');
		var ajaxurl = $('.fixed_headers').attr('url');
		
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				action: 'sam_clear_blacklist'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				showModalOkDialog(ajaxErrorMessage, response);

			},
			success: function(response){
					console.log( response.trim() );
					if (response.trim() == 1){
						$('td.blacklist-ip').addClass('hidden');
						$('td.command-column').addClass('hidden');
					}
					else{
						showModalOkDialog(ajaxErrorMessage, response);
					}
					
			}
		
		});	
	}


	// ======================================================= blacklist features end  ===============================================================




	// ======================================================= banlist features begin  ===============================================================


	$(document).on("click","button.add_ip_to_banlist",function(event){
		event.preventDefault();

		var ip = $('input.input_ip_to_banlist').val();
		var banPeriod = $('input.input_time_to_banlist').val();
		var ajaxErrorMessage = $('.fixed_headers_ban').attr('ajax-error-message');
			
		if (! validateBanPeriod( banPeriod )){
			showModalOkDialog("Error", 'Enter valid period');
			return;
		}
		
		var ajaxurl = $('.fixed_headers_ban').attr('url');
			
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'ip': ip,
				'period' : banPeriod,
				action: 'sam_add_ip_to_banlist'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				//$(loaderSel).removeClass(loadingClass);
				showModalOkDialog(ajaxErrorMessage, response);

			},
			success: function(response){
					console.log( response.trim() );
					
					var vars = response.trim().split('|');
					var res = vars[0];
					
					if (res == 1){
						var ipVal = vars[1];
						var startBanTime = vars[2];
						var endBanTime = vars[3];
						// append added IP to the blacklist
						var removeWord  = $('table.fixed_headers_ban').attr( 'remove-word' );
						var v = $( "<tr> <td class=\"blacklist-ip\">" + ipVal + 
										"<td class=\"banlist-starttime\">"  + startBanTime + "</td>" +
										"<td class=\"banlist-endtime\">"  + endBanTime + "</td>" +
										"<td class=\"command-column\"><a class=\"remove-from-banlist\" href=\"#\">" + removeWord + "</a></td> </tr>");
						$( "table .fixed_headers_ban tbody" ).append( v );
						// clear entries
						$('input.input_ip_to_banlist').val("");
						$('input.input_time_to_banlist').val("");
					}
					else if (res == 0){
						//wrong period
						var message = $('.fixed_headers_ban').attr('ajax-error-bad-period-message');
						showModalOkDialog(message, "");
					}
					else if (res == 2){
						// double ip
						var message = $('.fixed_headers_ban').attr('ajax-error-double-ip-message');
						showModalOkDialog(message, "");						
					}
					else if (res == 3){
						// double ip
						var message = $('.fixed_headers_ban').attr('ajax-error-bad-ip-message');
						showModalOkDialog(message, "");						
					}
					else{
						showModalOkDialog(ajaxErrorMessage, "");		
					}

					
			}
		
		});
			
			
	});


	function validateBanPeriod( period ){
		return true;
	}




	$(document).on("click","a.remove-from-banlist",function(event){
		event.preventDefault();

		var row = $(this).closest( "tr" );
		var ip = row.find('.banlist-ip').text();	
		
		var confirmMessage = $('table.fixed_headers_ban').attr( 'remove-confirm-message' ) + " " + ip + " ?";
		
		remove_from_banlist_yes.row = row;
		remove_from_banlist_yes.ip = ip;
		showModalYesNoDialog("", confirmMessage, remove_from_banlist_yes, function(){});
				
	});
	
	
	function remove_from_banlist_yes(){
		var row = remove_from_banlist_yes.row;
		var ip = remove_from_banlist_yes.ip;	
		var ajaxurl = $('table.fixed_headers_ban').attr('url');
		var ajaxErrorMessage = $('.fixed_headers_ban').attr('ajax-error-message');
		
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'ip': ip,
				action: 'sam_remove_ip_from_banlist'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				//$(loaderSel).removeClass(loadingClass);
				showModalOkDialog(ajaxErrorMessage, response);

			},
			success: function(response){
					console.log( response.trim() );
					if (response.trim() == 1){
						row.addClass('hidden');
					}
					else{
						showModalOkDialog(ajaxErrorMessage, "");
					}
					
			}
		
		});	
	}



	// clear banlist
	$(document).on("click","button.clear_banlist", function(event){
		event.preventDefault();
		
		var confirmMessage = $('table.fixed_headers_ban').attr( 'clear-confirm-message' );
		showModalYesNoDialog("", confirmMessage, clear_banlist_yes, function(){});
				
	});
	
	
	function clear_banlist_yes(){
		var ajaxErrorMessage = $('.fixed_headers_ban').attr('ajax-error-message');
		var ajaxurl = $('.fixed_headers_ban').attr('url');
		
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				action: 'sam_clear_banlist'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				showModalOkDialog(ajaxErrorMessage, "");

			},
			success: function(response){
					console.log( response.trim() );
					if (response.trim() == 1){
						$('.fixed_headers_ban tbody tr').addClass('hidden');
					}
					else{
						showModalOkDialog(ajaxErrorMessage, "");
					}
					
			}
		
		});		
	}



// ======================================================= banlist features end  ===============================================================




// ======================================================= banlist-number features begin  ===============================================================
	$(document).on("click","a.remove-from-bannumberlist",function(event){
		event.preventDefault();

			
		var row = $(this).closest( "tr" );
		var ip = row.find('.bannumberlist-ip').text();	
		
		var confirmMessage = $('table.fixed_headers_bannumber').attr( 'remove-confirm-message' ) + " " + ip + " ?";
		
		remove_from_bannumberlist_yes.row = row;
		remove_from_bannumberlist_yes.ip = ip;
		showModalYesNoDialog("", confirmMessage, remove_from_bannumberlist_yes, function(){});
					
	});
	
	function remove_from_bannumberlist_yes(){
		var row = remove_from_bannumberlist_yes.row;
		var ip = remove_from_bannumberlist_yes.ip;	
		var ajaxErrorMessage = $('.fixed_headers_bannumber').attr('ajax-error-message');
	
		var ajaxurl = $('.fixed_headers_bannumber').attr('url');
		
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				'ip': ip,
				action: 'sam_remove_ip_from_bannumberlist'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				showModalOkDialog(ajaxErrorMessage, "");

			},
			success: function(response){
					console.log( response.trim() );
				
					if (response.trim() == 1){
						row.addClass('hidden');
					}
					else{
						showModalOkDialog(ajaxErrorMessage, "");
					}
					
			}
		
		});		
	}
	
	
	
	
	// clear bannumberlist
	$(document).on("click","button.clear-banlist-number", function(event){
		event.preventDefault();
		
		var confirmMessage = $('table.fixed_headers_bannumber').attr( 'clear-confirm-message' );
		
		showModalYesNoDialog("", confirmMessage, clear_banlist_number_yes, function(){});
				
	});	
	
	function clear_banlist_number_yes(){
			
		var ajaxErrorMessage = $('.fixed_headers_bannumber').attr('ajax-error-message');	
		var ajaxurl = $('.fixed_headers_bannumber').attr('url');
		
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 7000, 
			data: {
				action: 'sam_clear_bannumberlist'
			},
			error: function(response){
				console.log("Server error occured!" + response);
				showModalOkDialog(ajaxErrorMessage, "");

			},
			success: function(response){
					console.log( response.trim() );
					if (response.trim() == 1){
						$('.fixed_headers_bannumber tbody tr').addClass('hidden');
					}
					else{
						showModalOkDialog(ajaxErrorMessage, "");
					}
					
			}
		
		});	
	}

// ======================================================= banlist-number features end  ===============================================================


   
	   
	   
	   
// ======================================================= statistics begin  ===============================================================	   


	setStartFilterOptions();
	setStartCleanOptions();	

	setCustomRefererField();

	$("#referer").change(function() {
		var chosenVal = $(this).find('option:selected').attr('value');
		setCustomRefererField();
	});
	
	
	function setStartFilterOptions(){
		// set year
		var fYearVal = $("#year-select-container").attr('year');
		$('#year-select').val(fYearVal);
		// set month
		var fMonthVal = $("#month-select-container").attr('month');
		$('#month-select').val(fMonthVal);
		// set referer
		var fRefererVal = $("#referer").attr('referer');
		$('#referer-select').val(fRefererVal);

	
	}
	
	function setStartCleanOptions(){
		// set  start year
		var sYearVal = $(".statistics-clean-year-start-container").attr('value');
		$('#statistics-clean-start-year').val(sYearVal);
		// set start month
		var sMonthVal = $(".statistics-clean-month-start-container").attr('value');
		$('#statistics-clean-start-month').val(sMonthVal);	
		
		// set  end year
		var eYearVal = $(".statistics-clean-year-end-container").attr('value');
		$('#statistics-clean-end-year').val(eYearVal);
		// set end month
		var eMonthVal = $(".statistics-clean-month-end-container").attr('value');
		$('#statistics-clean-end-month').val(eMonthVal);
		
	}
	

	function setCustomRefererField(){
		var chosenVal = $("#referer option:selected").attr('value');
		var customRefererField = $('#custom-referer');
		var customRefererId = $('#referer').attr('custom-referer-id');
		
		if (chosenVal == customRefererId){
			customRefererField.prop('disabled', false);
			customRefererField.removeClass('hidden');
			customRefererField.val('');
		}
		else{
			customRefererField.prop('disabled', true);
			customRefererField.addClass('hidden');		
		}
	}
	
	
	function getRefererValue(){
		var chosenVal = $("#referer option:selected").attr('value');
		var customRefererId = $('#referer').attr('custom-referer-id');
		//if custom referer selected, get the value from the field
		if (chosenVal == customRefererId){
			return $('#custom-referer').val().trim().toLowerCase();
		}
		else{
			return $("#referer option:selected").val().toLowerCase();
		}
	}
	
	
	// apply filter
	$(document).on("click","button.statistics-filter", function(event){
		event.preventDefault();
		
		var ajaxErrorMessage = $("button.statistics-filter").attr('ajax-error-message');
					
		var ajaxurl = $(this).attr('ajax-url');	
		var year = $('#year-select option:selected').attr('value');
		var month = $('#month-select option:selected').attr('value');
		var referer = getRefererValue();
		
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 10000, 
			data: {
				action: 'sam_update_statistics',
				'year' : year,
				'month' : month,
				'referer' : referer,
			},
			error: function(response){
				console.log("Server error occured!" + response);
				showModalOkDialog(ajaxErrorMessage, "");

			},
			success: function(response){
					var vars = response.trim().split('|');
					var res = vars[0];
					var value = vars[1];

					if (res.trim() == 1){
						$( ".chart-container" ).empty();
						var res = $( value.trim() );
						$( ".chart-container" ).append( res );
					}
					else{
						showModalOkDialog(ajaxErrorMessage, "");
					}
					
			}
		
		});
			
			
			
	});	
	
	
	
	
	
	// clear statistics
	$(document).on("click","button.clear-statistics", function(event){
			
		// get clean statistics settings
		var cleanYearStart = $('#statistics-clean-start-year option:selected').attr('value');
		var cleanMonthStart = $('#statistics-clean-start-month option:selected').attr('value');
		var cleanYearEnd = $('#statistics-clean-end-year option:selected').attr('value');
		var cleanMonthEnd = $('#statistics-clean-end-month option:selected').attr('value');	
		
		if ( !checkDates(cleanYearStart, cleanMonthStart, cleanYearEnd, cleanMonthEnd)){
			// show error message
			var message = $('button.clear-statistics').attr( 'data-bad-message' );
			showModalOkDialog("", message);
			return;
		}
		// format months
		cleanMonthStart = ("0" + cleanMonthStart).slice(-2);
		cleanMonthEnd = ("0" + cleanMonthEnd).slice(-2)		
		
		var confirmMessage = $('button.clear-statistics').attr( 'clear-confirm-message' ) + " " + cleanYearStart + "." + cleanMonthStart + " - " + cleanYearEnd + "." + cleanMonthEnd + " ?";		
		
		
		showModalYesNoDialog("", confirmMessage, clear_statistics_cinfirm_yes, clear_statistics_cinfirm_no);
			
	});	 


	function clear_statistics_cinfirm_no(){
		return;
	}
	
	function clear_statistics_cinfirm_yes(){
		// get clean statistics settings
		var cleanYearStart = $('#statistics-clean-start-year option:selected').attr('value');
		var cleanMonthStart = $('#statistics-clean-start-month option:selected').attr('value');
		var cleanYearEnd = $('#statistics-clean-end-year option:selected').attr('value');
		var cleanMonthEnd = $('#statistics-clean-end-month option:selected').attr('value');	
		
		var ajaxErrorMessage = $("button.clear-statistics").attr('ajax-error-message');
		
		
		var ajaxurl = $('button.clear-statistics').attr('ajax-url');	
		// get filter settings
		var filterYear = $('#year-select option:selected').attr('value');
		var filterMonth = $('#month-select option:selected').attr('value');
		var filterReferer = getRefererValue();
			
		$.ajax({
			url: ajaxurl, 
			type: 'post',
			timeout: 10000, 
			data: {
				action: 'sam_clean_statistics',
				'fyear' : filterYear,
				'fmonth' : filterMonth,
				'freferer' : filterReferer,
				'syear' : cleanYearStart,
				'smonth' : cleanMonthStart,
				'eyear' : cleanYearEnd,
				'emonth' : cleanMonthEnd
			},
			error: function(response){
				console.log("Server error occured!" + response);
				showModalOkDialog(ajaxErrorMessage, "");

			},
			success: function(response){
					var vars = response.trim().split('|');
					var res = vars[0];
					var value = vars[1];

					if (res.trim() == 1){
						$( ".chart-container" ).empty();
						var res = $( value.trim() );
						$( ".chart-container" ).append( res );
					}
					else{
						console.log("Server error occured!" + response);
						showModalOkDialog(ajaxErrorMessage, "");
					}
					
			}
		
		});		
	}

	
 	
	// find out if the statistics clean parametars are right
	function checkDates(sYear, sMonth, eYear, eMonth){
		// cast to int
		sYear = parseInt(sYear);
		sMonth = parseInt(sMonth);
		eYear = parseInt(eYear);
		eMonth = parseInt(eMonth);

		if (sYear > eYear){
			return false;
		}
		if ( (sYear == eYear) && (sMonth > eMonth) ){
			return false;
		}
		return true;
	}

// =======================================================statistics end  =================================================================	 


// =======================================================model window yes no  =================================================================	 

	var modalFunctionYes = null;
	var modalFunctionNo = null;
	var modalFunctionContext = null;
	// add modal dialog yes-no to DOM
	var mdw = $("  \
	  \
	  <div id='idmodalYesNo' class='w3-modal'>\
	    <div style='width: 500px; padding: 15px; text-align: center;' class='w3-modal-content w3-animate-top w3-card-4 modal-yes-no'>\
	      <header class='w3-container'> \
	        <span onclick='document.getElementById('idmodal').style.display='none'' \
	        class='w3-button w3-display-topright'>&times;</span>\
	        <h3 class='head-content'>Modal Header</h3>\
	      </header>\
			<div><p class='modal-dialog-content' style='font-size: 120%;'></p></div>\
			<div class='modal-dialog-button-wrapper'><button type='button' class='btn btn-default button-secondary modal-dialog-button-ok'>Ok</button>\
			&nbsp&nbsp&nbsp&nbsp&nbsp\
			<button type='button' class='btn btn-default modal-dialog-button-no button-secondary'>No</button>\
			</div>\
	\
	      <footer class='w3-container'>\
	        <p></p>\
	      </footer>\
	    </div>\
	  </div>\
	\
		");

	$('body').append(mdw);
	document.getElementById('idmodalYesNo').style.display='none';
		
	function showModalYesNoDialog(head, text, yesFunction, noFunction){
		var md = $('#idmodalYesNo');
		md.find('.head-content').text(head);
		md.find('.modal-dialog-content').empty();
		md.find('.modal-dialog-content').text(text);
		
		modalFunctionYes = yesFunction;
		modalFunctionNo = noFunction;
		document.getElementById('idmodalYesNo').style.display='block';		
	}
	

	// if close/no modal window button pressed
	$(document).on("click","#idmodalYesNo .w3-display-topright, #idmodalYesNo .modal-dialog-button-no", function(event){
		modalFunctionNo();	
		showModelYesNo(false);
	});
	
	// if yes modal window button pressed
	$(document).on("click","#idmodalYesNo .modal-dialog-button-ok", function(event){
		modalFunctionYes();	
		showModelYesNo(false);
	});	
	
	
	
	
	// add modal ok dialog to DOM
	var modalFunctionOk = null;
	var mdwOk = $("  \
	  \
	  <div id='idmodalOk' class='w3-modal'>\
	    <div style='width: 500px; padding: 15px; text-align: center;' class='w3-modal-content w3-animate-top w3-card-4 modal-yes-no'>\
	      <header class='w3-container'> \
	        <span onclick='document.getElementById('idmodal').style.display='none'' \
	        class='w3-button w3-display-topright'>&times;</span>\
	        <h3 class='head-content'>Modal Header</h3>\
	      </header>\
			<div><p class='modal-dialog-content' style='font-size: 120%;'></p></div>\
			<div class='modal-dialog-button-wrapper'><button type='button' class='btn btn-default button-secondary modal-dialog-button-ok'>Ok</button>\
			</div>\
	\
	      <footer class='w3-container'>\
	        <p></p>\
	      </footer>\
	    </div>\
	  </div>\
	\
		");

	$('body').append(mdwOk);
	document.getElementById('idmodalOk').style.display='none';	

	function showModalOkDialog(head, text, okFunction){
		var md = $('#idmodalOk');
		md.find('.head-content').text(head);
		md.find('.modal-dialog-content').empty();
		md.find('.modal-dialog-content').text(text);
		
		if (okFunction){
			modalFunctionOk = yesFunction;
		}
		else{
			modalFunctionOk = function(){};
		}
		document.getElementById('idmodalOk').style.display='block';		
	}
	
	
	// if close/ok modal window button pressed
	$(document).on("click","#idmodalOk .w3-display-topright, #idmodalOk .modal-dialog-button-ok", function(event){
		modalFunctionOk();	
		showModelOk(false);
	});


	function showModelOk(show){
		var p = 'none';
		if (show){
			p = 'block';	
		}
		else{
			p = 'none';	
		}
		
		document.getElementById('idmodalOk').style.display = p;	
	}	


 

});


function showModelYesNo(show){
	var p = 'none';
	if (show){
		p = 'block';	
	}
	else{
		p = 'none';	
	}
	
	document.getElementById('idmodalYesNo').style.display=p;	
}


