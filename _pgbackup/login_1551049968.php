<?php
include_once("functions.php");
?>
<!DOCTYPE html> 
<html lang="pt"> 
    <head> 
        <title>Eon Team Brasil | Portal</title>         
        <meta charset="UTF-8"> 
        <meta name="keywords" content="cloud, hosting, creative, html"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <!-- Favicon -->         
        <link href="/SharedAssets/img/raposa png.png" rel="shortcut icon"/> 
        <!-- Google Font -->         
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> 
        <!-- Stylesheets -->         
        <link rel="stylesheet" href="css/bootstrap.min.css"/> 
        <link rel="stylesheet" href="css/font-awesome.min.css"/> 
        <link rel="stylesheet" href="css/flaticon.css"/> 
        <link rel="stylesheet" href="css/owl.carousel.min.css"/> 
        <link rel="stylesheet" href="css/animate.css"/> 
        <link rel="stylesheet" href="css/style.css"/> 
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->         
        <link href="css/theme-dark.css" rel="stylesheet" type="text/css">
    </head>     
    <body> 
        <!-- Page Preloder -->         
        <div id="preloder"> 
            <div class="loader"></div>             
        </div>         
        <!-- Header section -->         
        <header class="header-section"> 
            <a href="./index.php" class="site-logo">
                <img src="/SharedAssets/img/logoraposa.png" alt style="width: 105px;
    height: 40px;">
            </a>             
            <div class="nav-switch"> 
                <i class="fa fa-bars"></i> 
            </div>             
            <div class="nav-warp"> 
                <ul class="main-menu" style="padding-top: 10px;"> 
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" style="padding-right: 0px;margin-left: 0px;border-top-width: 0px;padding-left: 30px;" aria-expanded="false"><span class="glyphicon glyphicon-log-in"></span> Login</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: relative;will-change: transform;top: 0px;left: 0px;transform: translate3d(50px, 22px, 0px);margin-right: 0px;border-bottom-width: 01px;padding-bottom: 15px;padding-top: 15px;">
                            <form id="FormLogin" name="FormLogin" class="form-horizontal" method="POST" action="javascript:FormLogin()"> 
                                <div class="form-group"> 
                                    <div class="col-md-12"> 
                                        <input type="text" name="usuario" class="form-control" placeholder="Usuário"/> 
                                    </div>                                     
                                </div>                                 
                                <div class="form-group"> 
                                    <div class="col-md-12"> 
                                        <input type="password" name="senha" class="form-control" placeholder="Senha"/> 
                                    </div>                                     
                                </div>                                 
                                <div class="form-group"> 
                                    <div id="StatusLogin"></div>                                     
                                    <div class="col-md-12"> 
                                        <button class="btn btn-info btn-block">Entrar</button>                                         
                                    </div>                                     
                                </div>                                 
                            </form>                             
                        </div>                         
                        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
                        <script>
</div></script>
                    </li>
                </ul>
            </div>             
            <!-- Start of WebFreeCounter Code -->
             <a href="https://www.webfreecounter.com/" target="_blank">
			    <img src="https://www.webfreecounter.com/hit.php?id=zuncxn&nd=6&style=36" border="0" alt="visitor counter">
			 </a>
            <!-- End of WebFreeCounter Code -->
        </header>         
        <!-- Header section end -->         
        <!-- Hero section -->         
        <section class="hero-section set-bg" data-setbg="img/bg.jpg"> 
            <div class="container h-100"> 
                <div class="hero-content text-white">
                    <div class="login-container"> 
                        <div class="login-box animated fadeInDown">
                            <div class="login-logo" style="margin-top: 100px;">Eon Team Brasil</div>                             
                        </div>                         
                    </div>
                    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
                    <script>
$(function(){

	$("#FormLogin").submit(function() {
		
		var formData = new FormData($(this)[0]);

		$.ajax({
			
			type: "POST",
			data: formData,
			async: true,
			url: "validar-login.php",
			success: function(result){
				$("#StatusLogin").html('');
				$("#StatusGeral").html('');
				$("#StatusGeral").append(result);
			},
			beforeSend: function(){
		  	  	$('#StatusLogin').html("<center><img src=\"img/owl/AjaxLoader.gif\"><br><br></center>");
		  	},
			cache: false,
        	contentType: false,
        	processData: false
	 	});
	});
});
</script>
                    <div id="StatusGeral"></div>                     
                    <div class="row"> 
                        <div class="col-lg-6 pr-0"> 
</div>                         
                    </div>                     
                    <div class="hero-rocket"> 
                        <img src="./img/rocket.png" alt=""> 
                    </div>                     
                </div>                 
            </div>             
        </section>         
        <!-- Hero section end -->         
        <!-- Features section -->         
        <!-- Features section end -->         
        <!-- Domain search section -->         
        <!-- Domain search section end -->         
        <!-- Skills & testimonials section -->         
        <!-- Skills & testimonials section end -->         
        <!-- Pricing section -->         
        <!-- Pricing section end -->         
        <!-- Banner section -->         
        <!-- Banner section end -->         
        <!-- Footer top section -->         
        <!-- Footer top section end -->         
        <!-- Footer section -->         
        <footer class="footer-section"> 
            <div class="container"> 
                <ul class="footer-menu"> 
                    <li></li>                     
                    <li></li>                     
                    <li></li>                     
                    <li></li>                     
                </ul>                 
                <div class="copyright">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->                     
                    Copyright &copy;
                    <script>document.write(new Date().getFullYear());</script>                                                                                                                                                                 All rights reserved | This template is made with 
                    <i class="fa fa-heart-o" aria-hidden="true"></i> by 
                    <a href="https://colorlib.com" target="_blank">Colorlib</a> 
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </div>                 
            </div>             
        </footer>         
        <!-- Footer top section end -->         
        <!--====== Javascripts & Jquery ======-->         
        <script src="js/jquery-3.2.1.min.js"></script>         
        <script src="js/bootstrap.min.js"></script>         
        <script src="js/owl.carousel.min.js"></script>         
        <script src="js/circle-progress.min.js"></script>         
        <script src="js/main.js"></script>         
    </body>     
</html>
