<nav class="navbar navbar-expand-lg navbar-white navbar-light" style="border: solid 1px #ccc; margin-top: 1%;">
	<a class="navbar-brand" href="#" id="menuTittle">Menú</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#PrimaryMenu"
	aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="PrimaryMenu">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<?php
				echo "<a href='index.php?controller=index&action=index&esp=".$id."' class='nav-link'>Generar Índice Electrónico</a>";
				?>
			</li>
			<li class="nav-item" style="border: solid 1px #236093;">
				<?php
				echo "<a href='index.php?controller=index&action=faqs&esp=".$id."' class='nav-link'>Instrucciones de uso - Soporte</a>";
				?>
			</li>
		</ul>
	</div>
</nav>
