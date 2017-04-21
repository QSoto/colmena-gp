<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Colmena - Sistema de Gestión de Talento Humano</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="colmena geek pro edition" />
		<meta name="author" content="Qsoto" />
        <!-- css -->
        <link href="/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/css/styles.css" rel="stylesheet">
        <link href="/css/super.css" rel="stylesheet" />

    </head>
    <body>
		<div class="container">
			@yield('content')
		</div>
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/main.js"></script>
		<script type="text/javascript">
			!function ($) {
			$(document).on("click","ul.nav li.parent > a > span.icon", function(){
				$(this).find('em:first').toggleClass("glyphicon-minus");
			});
			$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
			}(window.jQuery);

			$(window).on('resize', function () {
			  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
			})
			$(window).on('resize', function () {
			  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
			})
		</script>
	</body>
</html>