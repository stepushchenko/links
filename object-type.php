<?php
session_start();
require_once 'system-connection.php'; // подключаем базу данных
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);

$title = "Object type";
echo '
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  
	<title>'; echo $title; echo'</title>
</head>';





// Проводим операции на базе данных

	// Add object type

	if ($_POST["operation"]==="addOt") {
		
		$query = "INSERT INTO `ot`(`name`,`parent`) VALUES ('".$_POST['ot']."', '".$_POST['parent']."')";
		$query = mysqli_query($connection, $query);
	}

	// Edit object type

	if ($_POST["operation"]==="editOt") {
		
		$query = "UPDATE `ot` set 
		`name`='".$_POST['ot']."',
		`parent`='".$_POST['parent']."' 
		where `id`= '".$_POST['id']."'";
		$query = mysqli_query($connection, $query);
	}

// Получаем сведения из базы данных

	$query ="SELECT * FROM `ot`";
	$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
	while($row = mysqli_fetch_array($result)){
		$ot[$row['id']]['id'] = $row['id'];
		$ot[$row['id']]['name'] = $row['name'];
		$ot[$row['id']]['parent'] = $row['parent'];
		if ($row['parent']>0) {
			$ot[$row['parent']]['childArray'][] = $row['id'];
		}
	}

// Интерфейс

echo '<div class="container">';
include('header.php');



foreach ($ot as $row) {
	if ($row['parent'] == 0) {
		
		// Родитель
		
		echo '<div style="margin: 5px;">'; 
			echo $row['name']; 
			echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#add'; echo $row['id']; echo'">Add</button> ';
			echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#edit'; echo $row['id']; echo'">Edit</button> ';
			echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal">'; echo $row['id']; echo'</button> ';
		echo '</div>';
		
		// Дети
		
		if (is_array($ot[$row['id']]['childArray'])) {
			foreach ($ot[$row['id']]['childArray'] as $childrenArray) {
				echo '<div style="padding-left: 30px; margin: 5px;">'; 
					echo $ot[$childrenArray]['name']; 
					echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#add'; echo $childrenArray; echo'">Add</button> ';
					echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#edit'; echo $childrenArray; echo'">Edit</button> ';
					echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal">'; echo $childrenArray; echo'</button> ';
				echo '</div>';
				
				// Внуки
				
				if (is_array($ot[$childrenArray]['childArray'])) {
					foreach ($ot[$childrenArray]['childArray'] as $childrenArray2) {
						echo '<div style="padding-left: 60px; margin: 5px;">'; 
							echo $ot[$childrenArray2]['name']; 
							echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#add'; echo $childrenArray2; echo'">Add</button> ';
							echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#edit'; echo $childrenArray2; echo'">Edit</button> ';
							echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal">'; echo $childrenArray2; echo'</button> ';
						echo '</div>';
						
						// Правнуки
						
						if (is_array($ot[$childrenArray2]['childArray'])) {
							foreach ($ot[$childrenArray2]['childArray'] as $childrenArray3) {
								echo '<div style="padding-left: 90px; margin: 5px;">'; 
									echo $ot[$childrenArray3]['name']; 
									echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#add'; echo $childrenArray3; echo'">Add</button> ';
									echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#edit'; echo $childrenArray3; echo'">Edit</button> ';
									echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal">'; echo $childrenArray3; echo'</button> ';
								echo '</div>';
								
								// Праправнуки
						
								if (is_array($ot[$childrenArray3]['childArray'])) {
									foreach ($ot[$childrenArray3]['childArray'] as $childrenArray4) {
										echo '<div style="padding-left: 120px; margin: 5px;">'; 
											echo $ot[$childrenArray4]['name']; 
											echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#add'; echo $childrenArray4; echo'">Add</button> ';
											echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#edit'; echo $childrenArray4; echo'">Edit</button> ';
											echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal">'; echo $childrenArray4; echo'</button> ';
										echo '</div>';
										
										// Прапраправнуки
						
										if (is_array($ot[$childrenArray4]['childArray'])) {
											foreach ($ot[$childrenArray4]['childArray'] as $childrenArray5) {
												echo '<div style="padding-left: 150px; margin: 5px;">'; 
													echo $ot[$childrenArray5]['name']; 
													echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#add'; echo $childrenArray5; echo'">Add</button> ';
													echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#edit'; echo $childrenArray5; echo'">Edit</button> ';
													echo ' <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal">'; echo $childrenArray5; echo'</button> ';
												echo '</div>';
											}	
										}
									}	
								}
							}	
						}
					}	
				}
			}	
		}
	}
}

echo '<br><br><br>';

// Add object type

foreach ($ot as $row) {
	echo'
	<form action="object-type.php" method="post">
		<div class="modal fade" id="add'; echo $row['id']; echo'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add object type</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3 row">
						<label class="col-sm-4 col-form-label">Parent</label>
						<div class="col-sm-8">
							<input readonly type="text" class="form-control" value="'; echo $ot[$row['id']]['name']; echo'">
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-sm-4 col-form-label">Name</label>
						<div class="col-sm-8">
							<input name="ot" type="text" class="form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Ok</button>
				</div>
			</div>
		  </div>
		</div>
		<input type="hidden" name="parent" value="'.$row['id'].'">
		<input type="hidden" name="operation" value="addOt">
	</form>';
}

// Edit object type

foreach ($ot as $row) {
	echo'
	<form action="object-type.php" method="post">
		<div class="modal fade" id="edit'; echo $row['id']; echo'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit object type</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3 row">
						<label class="col-sm-4 col-form-label">Parent</label>
						<div class="col-sm-8">
							<select name="parent" class="form-select">
								<option value="0" selected> </option>';
								foreach($ot as $row2) {
									echo' <option '; if ($row2['id'] == $row['parent']) {echo ' selected=selected ';}  echo' value="'; echo $row2['id']; echo'">'; echo $row2['id']; echo ' - '; echo $row2['name']; echo'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-sm-4 col-form-label">Name</label>
						<div class="col-sm-8">
							<input name="ot" type="text" value="'; echo $row['name']; echo'" class="form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Ok</button>
				</div>
			</div>
		  </div>
		</div>
		<input type="hidden" name="id" value="'.$row['id'].'">
		<input type="hidden" name="operation" value="editOt">
	</form>';
}

echo '
</div>';
?> 