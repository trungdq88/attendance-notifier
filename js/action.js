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
				var subjectIds = d['subjectIds'];
				var email = d['email'];
				var emailFreq = d['emailFreq'];
				
				switchToUserPanel(name, sess, subjectIds, email, emailFreq);
			} else {
				//Login failed
				alert("Login failed");
			}

		},"json");
    });
});

function switchToUserPanel(name, sess, subjectIds, email, emailFreq) {
	$('#loginpanel').hide(400, function(){
		$('#lblUsername').html(name);
		$('#email').val(email);
		$('#frequen').val(emailFreq);
		loadSubjectIds(subjectIds);
	});
	$('#userpanel').show(400);
}
function getSubjectIds() {
	var ids = [];
	$('#selected-item').find('li').each(function(index, e){
		var _t = $(this);
		ids.push(_t.attr('id'));
	});
	return ids.toString();
}
function loadSubjectIds(ids) {
	var arrID = ids.split(",");
	for (id in arrID) {
		//alert(arrID[id]);
		$("li[id=" + arrID[id] + "]").children('span').click();
	}
}