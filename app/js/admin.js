$(document).ready(function() {	
	
	$('#rooms').click(function() {

        $.ajax( {
            type: 'POST',
            url: '../xml/rooms_to_xml.php',
            async: false,
            success: function(result){
				window.open('../xml/rooms.xml','_blank');
            }
        });

    });
	
	$('#users').click(function() {

        $.ajax( {
            type: 'POST',
            url: '../xml/users_to_xml.php',
            async: false,
            success: function(result){
				window.open('../xml/users.xml','_blank');
            }
        });

    });
});