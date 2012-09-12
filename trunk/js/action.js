$(document).ready(function(e) {
	$('#userpanel').hide(0);
    $('#btnLogin').click(function(e) {
        var username = $('#txtUsername').val();
		var password = $('#txtPassword').val();
		var data = {'do':'login', 'username':username, 'password':password};
		$.post('control.php', data, function(d){
			if (d) {
				//Login successful
				var name = d['name'];
				var sess = d['sess'];
				switchToUserPanel(name, sess);
			} else {
				//Login failed
				alert("Login failed");
			}

		},"json");
    });
});

function switchToUserPanel(name, sess) {
	$('#loginpanel').hide(400);
	$('#lblUsername').html(name);
	$('#userpanel').show(400);	
	
}