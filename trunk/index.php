<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="author" content="nhannvse60650"/>
    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <link rel="stylesheet" type="text/css" href="css/elastic.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/elastic.js"></script>
    <script type="text/javascript" src="js/getSubject.js"></script>
    <script type="text/javascript" src="js/action.js"></script>
    <title>Attendance Notifier</title>
    <link REL="SHORTCUT ICON" HREF="images/favicon.ico">
</head>
<body>
    <div id="wrapper" class="container">
        <div class="columns unit">
            <div id="header" class="column elastic">
            	<div id="header-image">
                <img src='images/email.png' width="80" height="80"/>
                <h1 id="header-text">Attendance Notifier</h1>
                </div>
            </div><!--end .container-->
        </div>
        <div id="content-wrap" >
            <div id="content" class="unit">
                <div id="loginpanel" class="columns same-height">

                    <div id="main-content-loginpanel" class="column elastic">
                        <div id="welcome">
                            <h1 style="font-size:large">Chào mừng các bạn đến với Attendance Notifier</h1>
                            <br/>
                            <hr/>
                            <h3>Giới thiệu</h3>
                            <p>Attendance Notifier là dịch vụ cho phép bạn theo dõi số buổi Absent của mình mỗi ngày.</p>
							<p>Dịch vụ sẽ gửi email về hộp thư của bạn mỗi khi bạn bị absent để bạn có thể theo dõi số buổi vắng học của mình, tránh bị cấm thi một cách lãng xẹt.</p>
                            <p>Dịch vụ cho phép bạn theo dõi số slot absent của tất cả các môn học. Gửi mail thông báo 2 lần một ngày (chỉ gửi nếu bạn absent).</p>
                            <hr/>
                            <h3>Chính sách bảo mật</h3>
                            <p>Đăng nhập vào dịch vụ bằng tài khoản và mật khẩu đăng nhập CMS.</p>
                            <p>Chúng tôi đảm bảo mật khẩu của bạn được giữ kín tuyệt đối, chỉ sử dụng vào việc duy nhất là để kiểm tra số buổi absent (công việc này do server tự động thực hiện mỗi ngày).
                            <hr/>
                            <h3>Về tác giả</h3>
                            <p>Dự án attendance-notifier mã nguồn mở trên Google Code: <a href="http://code.google.com/p/attendance-notifier/">Attendance Notifier</a></p>
                            <p>Tác giả: <a href="mailto:trungdqse60994@fpt.edu.vn">TrungDQSE60994@fpt.edu.vn</a>, <a href="mailto:nhannvse60650@fpt.edu.vn">NhanNVSE60650@fpt.edu.vn</a></p>
                        </div><!--end #welcome-->
                        
                    </div><!--end #main-content-->
                    <div id="sidebar" class="column fixed equal-height" style="width:335px;">
                        <div id="login-form">
                            <h1>Đăng Nhập <span style="font-size:small;color:#999">(Bằng tài khoản CMS)</span></h1>
                            <form>
                            	<p>
                                    <label for="username">Tài khoản:</label>
                                    <input id="txtUsername" required pattern="\w{5,}" 
                                    type="text" name="username" onkeydown="if(event.keyCode == 13) {$('#btnLogin').click();}" id="username" />
                                </p>
                                <p>
                                    <label for="password">Mật khẩu:</label>
                                    <input id="txtPassword" required pattern=".{5,}" 
                                    type="password" name="password" onkeydown="if(event.keyCode == 13) {$('#btnLogin').click();}"  id="password" />
                                </p>
                                <p>
                                	<div id="status-and-login">
                                        <div id="btnLogin-div">
                                        	<input id="btnLogin" type="button" name="submit" value="Đăng nhập" />
                                        </div>
                                        <img id="status-image" src='images/loading.gif' />
	                                    <span id="status"></span> 
                                    </div>
                                </p>
                            </form><!--end form-->
                        </div><!--end login-form-->
                    </div><!--end #sidebar-->
                </div>
                
                <div id="userpanel" class="columns same-height">
                	<div id="main-content-userpanel" class="column">
                        <div id="userpanel-welcome">
                            <h1>Xin chào <span id="lblName"></span>!</h1>
                            <span class="hidden" id="lblUsername"></span><span class="hidden" id="lblSession"></span>
                            <p></p>
                        </div><!--end #userpanel-welcome-->
                        
                        <div id="select-box">
                        	<h3>Các môn học</h3>
                            <div id="filter-category" class="clearfix"></div>
                            <div id="select-item" class="clearfix">
                            </div>
                        </div>
                        <div id="selected-item">
                              <h3>Các môn đã được chọn</h3>
                              <div id="select-item-content">
                                  <p class="empty">Bạn chưa chọn môn nào</p>
                                  <ul>

                                  </ul>
                              </div><!--end select-item-content-->
                         </div><!--end #selected-item-->


                        <div id="button-area">
                            <input id="save-button" type="button" value="Lưu Thông Tin">
                            <input id="stop-service" type="button" value="Thoát">
                        </div><!--end control-area-->

                        <div id="user-requirement" class="elastic">
                        	<h3>Cài đặt</h3>
                            <form>
                                <p>
                                    <label for="email">Địa Chỉ Email Nhận Thông Báo:</label>
                                    <input id="email" type="text" name="email" />
                                </p>
                                <p>
                                    <label for="frequen">Tần Suất Gửi Email:</label>
                                    <select name="frequen" id="frequen">
                                        <option value="1">Mỗi ngày</option>
                                        <option value="2">2 ngày</option>
                                        <option value="3">3 ngày</option>
                                        <option value="4">Không nhận mail</option>
                                    </select>
                                </p>
                            </form><!--end form-->
                        </div><!--end user-requirement-->
                        <!--end select-box-->
                    </div><!--end #main-content-->
                    
                </div><!--end userpanel-->
            </div><!--end #content-->
        </div>

        <div id="footer" class="columns unit clearfix">
            <div class="elastic clearfix">
                <p>&copy; 2012 | Attendance Checker</p>
            </div>
        </div><!--end footer-->
        <div class="clear"></div>
    </div><!--end #wrapper-->
</body>
</html>