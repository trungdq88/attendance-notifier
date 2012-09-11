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
    <title>Front End</title>
</head>
<body>
    <div id="wrapper" class="container">
        <div class="columns unit">
            <div id="header" class="column elastic">
                <h1><a href="#">Logo</a></h1>
            </div><!--end .container-->
        </div>
        <div id="content-wrap" >
            <div id="content" class="unit">
                <div class="columns same-height">

                    <div id="main-content" class="column elastic">
                        <div id="welcome">
                            <h1>Chào mừng các bạn đến với Attendance Checker</h1>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi eu neque in massa tristique aliquet fringilla a libero.
                                Vestibulum porttitor ligula ut tellus vestibulum pharetra. Donec luctus dui et eros vehicula sed ullamcorper enim vehicula.
                                Maecenas dapibus elit in odio elementum non fringilla felis tincidunt. Donec pretium pretium consectetur.
                            </p>
                        </div><!--end #welcome-->
                        <div id="select-box">
                            <div id="filter-category"></div>
                            <div id="select-item">
                                <form action="" method="">

                                </form>
                            </div>
                        </div><!--end select-box-->
                    </div><!--end #main-content-->
                    <div id="sidebar" class="column fixed equal-height" style="width:335px;">
                        <div id="login-form">
                            <h1>Đăng Nhập</h1>
                            <form action="" method="POST">
                                <p>
                                    <label for="email">Email:</label>
                                    <input type="text" name="email" id="email" />
                                </p>
                                <p>
                                    <label for="password">Password:</label>
                                    <input type="text" name="password" id="password" />
                                </p>
                                <p>
                                    <input type="submit" name="submit" value="Send" />
                                </p>
                            </form><!--end form-->
                        </div><!--end login-form-->
                    </div><!--end #sidebar-->
                </div>
            </div><!--end #content-->
        </div>

        <div id="footer" class="columns unit">
            <div class="elastic">
                <p>&copy; 2012 | Attendance Checker</p>
            </div>
        </div><!--end footer-->
        <div class="clear"></div>
    </div><!--end #wrapper-->
</body>
</html>