$(document).ready(function(e) {
	$('#userpanel').hide(0);
    $('#btnLogin').click(function(e) {
        var username = $('#txtUsername').val();
		var password = $('#txtPassword').val();
		var data = {'do':'login',
					'username':username,
					'password':password};
		$.post('control.php', data, function(d){
			if (d) {
				//Login successful
				var name = d['Name'];
				var sess = d['Session'];
				var subjectIds = d['SubjectIds'];
				var email = d['Email'];
				var emailFreq = d['EmailFreq'];
				switchToUserPanel(name, sess, subjectIds, email, emailFreq);
			} else {
				//Login failed
				alert("Login failed");
			}

		},"json");
    });
	$('#save-button').click(function(e) {
		var data = {'do':'savesetting',
					'username':$('#lblUsername').html(),
					'session': $('#lblSession').html(),
					'subjectids':getSubjectIds(),
					'email':$('#email').val(),
					'emailfreq':$('#frequen').val()};
		$.post('control.php', data, function(d) {
			if (d == "YES") {
				alert("Các thay đổi đã được lưu thành công.");
			} else {
				alert("Có lỗi xảy ra:\n\n" + d);
			}
		});
	});
});

function switchToUserPanel(name, sess, subjectIds, email, emailFreq) {
	$('#loginpanel').hide(400, function(){
		$('#lblUsername').html($('#txtUsername').val());
		$('#lblName').html(name);
		$('#email').val(email);
		$('#frequen').val(emailFreq);
		$('#lblSession').html(sess);
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