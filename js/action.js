$(document).ready(function(e) {
	//$('#userpanel').hide(0);
	$('#showWelcome').click(function(e){
		$('#tut').fadeOut(300,function(){
			$('#welcome').fadeIn(300);	
		});
	});
	$('#showTut').click(function(e){
		$('#welcome').fadeOut(300,function(){
			$('#tut').fadeIn(300);	
		});
	});
	
    $('#btnLogin').click(function(e) {
		waitting(false);
		showStatus("Đang kết nối...", true);
        var username = $('#txtUsername').val();
		var password = $('#txtPassword').val();
		var data = {'do':'login',
					'username':username,
					'password':password};
		showStatus("Kết nối đến CMS...", true);
		$.ajax({
			type: "POST",
			url: "control.php",
			data: {'do':'login',
					'username':username,
					'password':password},
			dataType: "json",
			timeout: 20000, // in milliseconds
			success: function(d) {
				showStatus("Đang đăng nhập...", true);
				waitting(true);
				if (d) {
					showStatus("Đăng nhập thành công!", false);
					//Login successful
					var name = d['Name'];
					var sess = d['Session'];
					var subjectIds = d['SubjectIds'];
					var email = d['Email'];
					var emailFreq = d['EmailFreq'];
					switchToUserPanel(name, sess, subjectIds, email, emailFreq);
				} else {
					//Login failed
					showStatus("Sai mật khẩu!", false);
				}
			},
			error: function(request, status, err) {
				waitting(true);
				showStatus("Thất bại: " + status + " (" + err + ")", false);
				if(status == "timeout") {
					
				}
			}
		});
    });
	$('#save-button').click(function(e) {
		$('#save-button').hide();
		$.ajax({
			type: "POST",
			url: "control.php",
			data: {'do':'savesetting',
					'username':$('#lblUsername').html(),
					'session': $('#lblSession').html(),
					'subjectids':getSubjectIds(),
					'email':$('#email').val(),
					'emailfreq':$('#frequen').val()},
			timeout: 10000, // in milliseconds
			success: function(d) {
				if (d == "YES") {
					alert("Các thay đổi đã được lưu thành công.");
				} else {
					alert("Có lỗi xảy ra:\n\n" + d);
				}
				$('#save-button').fadeIn(300);
			},
			error: function(request, status, err) {
				alert("Có lỗi xảy ra: " + err + "\nXin thử lại!");
				$('#save-button').fadeIn(300);
			}
		});
	});
	
	$('#stop-service').click(function(e){
		document.location.reload(true);
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

function showStatus(status, loading) {
	$('#status').html(status);
	if (loading) {
		$('#status-image').fadeIn(200);
	} else {
		$('#status-image').fadeOut(200);
	}
}

function waitting(end){
	if (end) {
		$('#btnLogin').fadeIn(300);
		$('#txtUsername').removeAttr("disabled");
		$('#txtPassword').removeAttr("disabled");
	} else {
		$('#btnLogin').fadeOut(300);
		$('#txtUsername').attr("disabled","disabled");
		$('#txtPassword').attr("disabled","disabled");
	}
}