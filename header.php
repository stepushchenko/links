<?php

 
echo'
<div class="row align-items-start">
	<div class="col">
		<h1 class="display-1">'; echo $title; echo'</h1>
	</div>
	<div class="col"><br><br>
		<ul class="nav justify-content-end">
			<li class="nav-item">
				<a class="nav-link" href="/relation.php">Relation</a>
			</li>
			<li class="nav-item">
				<a class="nav-link " aria-current="page" href="/navigation.php">Navigation</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					...
				</a>
				<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
					<li><a class="nav-link" href="/object-type.php">Object type</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
<br><br><br>';

?>