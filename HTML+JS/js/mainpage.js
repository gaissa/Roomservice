$(document).ready(function() {	
	
	$(function() {
	$(".error").hide();
	
	$("#submitReg").click(function () {
	
		$(".error").hide();
		var username = $("input#username1").val();
		if (username == "") {
			$("label#name_error").show();
			$("input#name").focus();
			return false;
		}
		var password1 = $("input#password1").val();
		if (password1 == "") {
			$("label#password1_error").show();
			$("input#name").focus();
			return false;
		}	
		var password2 = $("input#password2").val();
		if (password2 == "") {
			$("label#password2_error").show();
			$("input#name").focus();
			return false;
		}	
		var email = $("input#email").val();
		if (email == "") {
			$("label#email_error").show();
			$("input#name").focus();
			return false;
		}	
						  
		var dataArray = {username: username, password1: password1, password2: password2, email: email};
		
		$.ajax({
			type: "POST",
			url: "php/register.php",
			data: { 'dataArray': dataArray},
			success: function() {
			  $('#reg_panel').html("<div id='message'></div>");
			  $('#message').html("<h2>Registration complete!</h2>")
			  .append("<p>You can now login.</p>")  
			  .hide()
			  .fadeIn(1000, function() {
				$('#message').append("<img id='checkmark' src='images/check.png' />");
			  });
			}
		  });
		return false;
			
		});		
	});
		
	$("div#login").click(function() {
		if ($("div#log_panel").is(':hidden')) {
			console.log('hid');
			$("div#log_panel").show();
				complete_log();
			}
				
		if ($("div#reg_panel").is(':visible')) {
			console.log('log');
			$("div#reg_panel").animate(
				{height:"0", opacity:"0"}, {duration:200}, {done:complete_log()}
			);					
			}	
						
		});	
			
			$("div#register").click(function() {
				if ($("div#reg_panel").is(':hidden')) {
				console.log('reghid');
				$("div#reg_panel").show();
				complete_reg();
				}
					
				if ($("div#log_panel").is(':visible')) {
					console.log('reg');
					$("div#log_panel").animate(
							{ height:"0", opacity:"0"}, {duration:200}, {done:complete_reg()}
							);
							
					
				}					
					
			});		
			
			hide_reg = function() {
				$("div#reg_panel").animate(
							{"height": 0, opacity:"0"}, 1000);
			}
			
			hide_log = function() {
				$("div#log_panel").animate(
							{"height": 0, opacity:"0"}, 1000);
			}
			
			complete_reg = function() {
				
				$("div#reg_panel").animate(
							{"height": 400, opacity:"100"}, 1000);
							
			}
			
			complete_log = function() {
				
				$("div#log_panel").animate(
							{"height": 142, opacity:"100"}, 1000);
			}
				
});

