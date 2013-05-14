// Checks if user exists
var userExists;
// Checks if email exists
var emailExists;
// Array holding the user data
var dataArray;
// Password
var password;

// Show dialog
function showDialog() {
			
			 $('#dialog').text("PASKAA");  
			
            $( "#dialog" ).dialog({

                resizable: false,
                height: 440,
                width: 440,
                modal: true,

            });
        
   }

// Checks if email is valid
function validateEmail(epost) {

	var emailRegex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	
	if (epost === "") {
			$("label#email_error").text("Kenttä vaaditaan");
			$("label#email_error").show();
			$("input#email").focus();
			return false;
	} else if (!emailRegex.test(epost)) {
			$("label#email_error").text("Anna validi sähköpostiosoite");
			$("label#email_error").show();
			$("input#email").focus();
			return false;
	} else { return true; }
}

// Checks if username is valid
function validateUsername(usernamee) {
	if (usernamee == "") {
			$("label#name_error").text("Kenttä vaaditaan");
			$("label#name_error").show();
			$("input#name").focus();
			return false;
		} else if (usernamee.length < 4) {
			$("label#name_error").text("Täytyy olla enemmän kuin 5 kirjainta");
			$("label#name_error").show();
			$("input#name").focus();
			return false;
		} else { return true; }
	
}

// Checks if first password field is valid
function validatePassword1(pw) {
	if (pw == "") {
			$("label#password_error1").text("Kenttä vaaditaan");
			$("label#password_error1").show();
			$("input#password1").focus();
			return false;
		} else if (pw.length < 7) {
			$("label#password_error1").text("Täytyy olla enemmän kuin 8 kirjainta");
			$("label#password_error1").show();
			$("input#password1").focus();
			return false;
		} else { return true; }
		
}

// Checks if second password field is valid
function validatePassword2(pw) {
	if (pw == "") {
			$("label#password_error2").text("Kenttä vaaditaan");
			$("label#password_error2").show();
			$("input#password2").focus();
			return false;
		} else if (pw.length < 7) {
			$("label#password_error2").text("Täytyy olla enemmän kuin 8 kirjainta");
			$("label#password_error2").show();
			$("input#password2").focus();
			return false;
		} else { return true; }
		
}

/* Checks if username or email already exist in the database.
   If they exists, throw error, else submit data. */
function validateData(dataArray) {
	$.ajax({
			type: "POST",
			url: "php/register.php",
			data: { 'dataArray': JSON.stringify(dataArray)},
			dataType: 'json',
			success: function(data) {
			  userExists = data.username;
			  emailExists  = data.email;
			  
			  if ((userExists === 0) && (emailExists === 0)) {
					sendData(dataArray);
			  } else if (userExists === 1) {
					$("label#name_error").text("Käyttäjänimi on jo käytössä");
					$("label#name_error").show();				
			  } 
			  if (emailExists === 1) {
					$("label#email_error").text("Sähköpostiosoite on jo käytössä");
					$("label#email_error").show();
			  } 
			  if ((userExists === 1) && (emailExists === 1)) {
					$("label#name_error").text("Käyttäjänimi on jo käytössä");
					$("label#name_error").show();
					$("label#email_error").text("Sähköpostiosoite on jo käytössä");
					$("label#email_error").show();
			  }
			 return false;
			}
		  });
}

// Sends user data to the database
function sendData(dataArray) {
	$.ajax({
			type: "POST",
			url: "php/register.php",
			data: { 'dataArray': JSON.stringify(dataArray)},
			dataType: 'json',
			success: function(data) {
			  userExists = data.username;
			  emailExists  = data.email;
			  $('div#reg_panel').hide();	
			  $('#message_panel').show();
			  $('#message_panel').html("<div id='message'></div>");
			  $('#message').html("<h2>Rekisteröityminen onnistui!</h2>")
			  .append("<p>Voit nyt kirjautua sisään.</p>")  
			  .hide()
			  .fadeIn(1000, function() {
				$('#message').append("<img id='checkmark' src='img/check.png' />");
			  });
			  
			 $("#registerForm").trigger('reset');
			 return false;
			}
		  });
}

// Called when document is loaded and ready
$(document).ready(function() {	
		
	$(function() {
		$(".error").hide();
	
		$("#submitReg").click(function (event) {
			event.preventDefault();
			var username = $("input#username1").val();
			var password1 = $("input#password1").val();
			var password2 = $("input#password2").val();
			var email = $("input#email").val();
					
			$(".error").hide();
			
			var user = validateUsername(username);
			
			var pw1 = validatePassword1(password1);
			
			var pw2 = validatePassword2(password2);
					
			var emaill = validateEmail(email);
			
			if (password1 !== password2) {
				alert("Passwords don't match");
				return false;
			} else if (password1 === password2) {
				var hash = CryptoJS.SHA1(password1);
				password = hash.toString(CryptoJS.enc.Hex);
			}						  
			
			dataArray = {username: username, password: password, email: email};			
			
			if ((user == true) && (pw1 == true) && (pw2 == true) && (emaill == true)) {		
				validateData(dataArray);
				} else { 
				return false; 
				}
			});			
		
	});
		
	// LOGIN CLICKED
	$("div#login").click(function() {
		
		if ($("div#message_panel").is(':visible')) {
			$("div#message_panel").hide();
			$("div#log_panel").show('normal');
			complete_log();
		}	
		if ($("div#log_panel").is(':hidden')) {
			$("div#log_panel").show('normal');
				complete_log();
		}
			
		if ($("div#reg_panel").is(':visible')) {
			$("div#log_panel").animate(
				{height:"142"}, {duration:300}, {done:hide_reg()}
			);					
		}						
		});	
	
	// REGISTER CLICKED	
	$("div#register").click(function() {
					
		if ($("div#reg_panel").is(':hidden')) {
			$("div#message_panel").hide();
			$("div#reg_panel").show('normal');
			complete_reg();
		}
					
		if ($("div#log_panel").is(':visible')) {
			console.log('reg');
				$("div#reg_panel").animate(
					{ height:"300"}, {duration:300}, {done:hide_log()}
					);
				}					
	});		
			
			// Hides registration panel
			hide_reg = function() {
				$("div#reg_panel").animate(
							{"height": 0}, 500);
			}
			// Hides login panel
			hide_log = function() {
				$("div#log_panel").animate(
							{"height": 0}, 500);
			}
			// Shows and animates registration panel
			complete_reg = function() {				
				$("div#reg_panel").animate(
							{"height": 400, opacity:"100"}, 1000);		
			}
			// Shows and animates login panel
			complete_log = function() {
				
				$("div#log_panel").animate(
							{"height": 142, opacity:"100"}, 1000);
			}
				
});

