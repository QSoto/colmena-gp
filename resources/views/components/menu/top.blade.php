<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{url("/")}}"><span>Colmena</span>GP Edition</a>
			<ul class="user-menu">
				@if(Auth::check())
					<li class="dropdown pull-right">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> {{Auth::user()->fullname}} <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="{{url("/usuarios/perfil/".Auth::user()->id)}}"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Perfil</a></li>
						{{--<li><a href="/usuarios/editar/{{Auth::user()->id}}"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg> Ajustes</a></li>--}}
						<li><a href="{{url("/logout")}}"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Salir</a></li>
					</ul>
				</li>
				@endif
			</ul>
		</div>

	</div><!-- /.container-fluid -->
</nav>
