<?php
session_start();
require_once 'system-connection.php'; // подключаем базу данных
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);

$title = "Relation";
echo '
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  
	<title>'; echo $title; echo'</title>
</head>';

if ($_POST["operation"]==="choose") {  
	$_SESSION['o1'] = $_POST['o'];
}
if (isset($_GET['o'])) { 
	$_SESSION['o1'] = $_GET['o'];
}


// Проводим операции

	// Add Relation

	if ($_POST["operation"]==="addR") {
		
		$query = "INSERT INTO `o`(`name`,`ot`) VALUES ('".$_POST['o']."', '".$_POST['ot']."')";
		$query = mysqli_query($connection, $query);
		$o2 = mysqli_insert_id($connection);
		
		$query = "INSERT INTO `r`
		(`o1`,`o2`,`rt1`,`rt2`) VALUES 
		('".$_POST['o1']."', '".$o2."', '".$_POST['rt1']."', '".$_POST['rt2']."')";
		$query = mysqli_query($connection, $query);
		$copy = mysqli_insert_id($connection);
		
		$query = "INSERT INTO `r`
		(`o1`,`o2`,`rt1`,`rt2`,`copy`) VALUES 
		('".$o2."', '".$_POST['o1']."', '".$_POST['rt2']."', '".$_POST['rt1']."', '".$copy."')";
		$query = mysqli_query($connection, $query);
		$backlink = mysqli_insert_id($connection);
		
		$query = "UPDATE `r` set 
		`copy`='".$backlink."'
		where `id`= '".$copy."'";
		$query = mysqli_query($connection, $query);
		
		if ($_POST['connect']>0) {
			
			$query = "INSERT INTO `r`
			(`o1`,`o2`) VALUES 
			('".$_POST['connect']."', '".$o2."')";
			$query = mysqli_query($connection, $query);
			$copy = mysqli_insert_id($connection);
			
			$query = "INSERT INTO `r`
			(`o1`,`o2`,`copy`) VALUES 
			('".$o2."','".$_POST['connect']."','".$copy."')";
			$query = mysqli_query($connection, $query);
			$backlink = mysqli_insert_id($connection);
			
			$query = "UPDATE `r` set 
			`copy`='".$backlink."'
			where `id`= '".$copy."'";
			$query = mysqli_query($connection, $query);
		}
	}
	
	// Edit Relation

	if ($_POST["operation"]==="edit") {
		
		$query = "UPDATE `o` set 
		`name`='".$_POST['o']."',
		`ot`='".$_POST['ot']."' 
		where `id`= '".$_POST['o2']."'";
		$query = mysqli_query($connection, $query);
		
		$query = "UPDATE `r` set 
		`rt1`='".$_POST['rt1']."',
		`rt2`='".$_POST['rt2']."'
		where `id`= '".$_POST['id']."'";
		$query = mysqli_query($connection, $query);
		
		$query = "UPDATE `r` set 
		`rt1`='".$_POST['rt2']."',
		`rt2`='".$_POST['rt1']."'
		where `id`= '".$_POST['copy']."'";
		$query = mysqli_query($connection, $query);
		
		if ($_POST['connect']>0) {
			
			$query = "INSERT INTO `r`
			(`o1`,`o2`) VALUES 
			('".$_POST['connect']."', '".$_POST['o2']."')";
			$query = mysqli_query($connection, $query);
			$copy = mysqli_insert_id($connection);
			
			$query = "INSERT INTO `r`
			(`o1`,`o2`,`copy`) VALUES 
			('".$_POST['o2']."','".$_POST['connect']."','".$copy."')";
			$query = mysqli_query($connection, $query);
			$backlink = mysqli_insert_id($connection);
			
			$query = "UPDATE `r` set 
			`copy`='".$backlink."' 
			where `id`= '".$copy."'";
			$query = mysqli_query($connection, $query);
		}
	}
	
	// Delete Relation

	if ($_POST["operation"]==="deleteR") {
		
		$query = "DELETE FROM `r` where `id`= '".$_POST['id']."'";
		$query = mysqli_query($connection, $query);
		
		$query = "DELETE FROM `r` where `id`= '".$_POST['copy']."'";
		$query = mysqli_query($connection, $query);
	}

	// Add Object type

	if ($_POST["operation"]==="addEt") {
		
		$query = "INSERT INTO `ot`(`name`) VALUES ('".$_POST['ot']."')";
		$query = mysqli_query($connection, $query);
		$query = mysqli_insert_id($connection);
	}
	
	// Add Relation type

	if ($_POST["operation"]==="addRt") {
		
		$query = "INSERT INTO `rt`(`name`) VALUES ('".$_POST['rt']."')";
		$query = mysqli_query($connection, $query);
		$query = mysqli_insert_id($connection);
	}

// Получаем сведения из базы данных

$query ="SELECT * FROM `ot` order by `name`";
$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
while($row = mysqli_fetch_array($result))
{
	$ot[$row['id']]['id'] = $row['id'];
	$ot[$row['id']]['name'] = $row['name'];
}

$query ="SELECT * FROM `o` order by `ot`,`name`";
$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
while($row = mysqli_fetch_array($result))
{
	$o[$row['id']]['id'] = $row['id'];
	$o[$row['id']]['name'] = $row['name'];
	$o[$row['id']]['ot'] = $row['ot'];
	$o[$row['id']]['otValue'] = $ot[$row['ot']]['name'];
	
	if ($row['ot']<3) {
		$oGeneral[$row['id']]['id'] = $row['id'];
		$oGeneral[$row['id']]['name'] = $row['name'];
	}
}

$query ="SELECT * FROM `rt`";
$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
while($row = mysqli_fetch_array($result))
{
	$rt[$row['id']]['id'] = $row['id'];
	$rt[$row['id']]['name'] = $row['name'];
}

$query ="SELECT * FROM `r` where `o1`='".$_SESSION['o1']."' order by `rt1` desc";
$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
while($row = mysqli_fetch_array($result))
{
	$rCurrent[$row['id']]['id'] = $row['id'];
	$rCurrent[$row['id']]['o1'] = $row['o1'];
	$rCurrent[$row['id']]['o2'] = $row['o2'];
	$rCurrent[$row['id']]['rt1'] = $row['rt1'];
	$rCurrent[$row['id']]['rt2'] = $row['rt2'];
	$rCurrent[$row['id']]['copy'] = $row['copy'];
}




// Интерфейс

echo '<div class="container">';
include('header.php');
	
	echo '
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addR">Add relation</button>
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRt">Add relation type</button>
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOt">Add object type</button>
	<br><br><br>
	
	<form action="relation.php" method="post">
		<div class="input-group mb-3">
		  <select name="o" class="form-select">
			<option selected>Выберите...</option>';
			foreach ($oGeneral as $OGeneral) {
				echo '<option '; if ($OGeneral['id'] == $_SESSION['o1']) {echo ' selected=selected ';}  echo ' value="'.$OGeneral['id'].'">'.$OGeneral['name'].'</option>';
			}
			echo'
		  </select>
		  <button class="btn btn-outline-secondary" type="submit">Choose</button>
		  <input type="hidden" name="operation" value="choose">
		</div>
	</form><br>';
	
	
	echo '
	<table class="table table-hover">
		<thead>
			<tr>
				<th scope="col">ID Object</th>
				<th scope="col">Object type</th>
				<th scope="col">Relation</th>
				<th scope="col">Relation type</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody>';
	if(is_array($rCurrent)) {
		foreach ($rCurrent as $r) {
				
			echo '
			<tr>
				<th scope="row">'; echo $r['o2']; echo'</th>
				<td>';	
					echo $o[$r['o2']]['otValue'];
					echo '
				</td>
				<td>';
				
					echo '<a href="/relation.php?o='; echo $r['o2']; echo'">';
						echo $o[$r['o2']]['name'];
					echo '</a>';
					echo'
				</td>
				<td>';
					if ($r['rt2']>0) {
						echo $rt[$r['rt2']]['name'];
					}
					 
					echo'
				</td>
				<td>
					<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete'.$r['o2'].'" >Delete</button>
					<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit'.$r['o2'].'">Edit</button>
				</td>
			</tr>';
		}
	} 
	echo '
		</tbody>
	</table>';
	

// Форма добавления


echo'
<form action="relation.php" method="post">
	<div class="modal fade" id="addR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Relation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Parent</label>
					<div class="col-sm-8">
						<input readonly type="text" class="form-control" value="'; echo $o[$_SESSION['o1']]['name']; echo'">
					</div>
				</div>
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Type</label>
					<div class="col-sm-8">
						<select name="ot" class="form-select">
							<option value="0" selected> </option>';
							foreach($ot as $Ot) {
								echo' <option value="'; echo $Ot['id']; echo'">'; echo $Ot['name']; echo'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Name</label>
					<div class="col-sm-8">
						<input list="datalistOptions" name="o" type="text" class="form-control">
						<datalist id="datalistOptions">';
							foreach ($o as $O) {
								echo '<option value="'.$O['name'].'">';
							}
							echo '
						</datalist>
					</div>
				</div>
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Relation -></label>
					<div class="col-sm-8">
						<select name="rt2" class="form-select">
							<option value="0"> </option>';
							foreach($rt as $Rt) {
								echo' <option value="'; echo $Rt['id']; echo'">'; echo $Rt['name']; echo'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Relation <-</label>
					<div class="col-sm-8">
						<select name="rt1" class="form-select">
							<option value="0"> </option>';
							foreach($rt as $Rt) {
								echo' <option value="'; echo $Rt['id']; echo'">'; echo $Rt['name']; echo'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Start</label>
					<div class="col-sm-8">
						<input name="interval_start" type="date" class="form-control" value="">
					</div>
				</div>
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Stop</label>
					<div class="col-sm-8">
						<input name="interval_stop" type="date" class="form-control" value="">	
					</div>
				</div>
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Connect</label>
					<div class="col-sm-8">
						<select name="connect" class="form-select">
							<option value="0"> </option>';
							if(is_array($rCurrent)) {
								foreach ($rCurrent as $r) {
									echo' <option value="'; echo $r['o2']; echo'">'; echo $o[$r['o2']]['otValue']; echo ' --> '; echo $o[$r['o2']]['name']; echo'</option>';
								}
							}
							echo'
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Ok</button>
			</div>
		</div>
	  </div>
	</div>
	<input type="hidden" name="o1" value="'.$_SESSION['o1'].'">
	<input type="hidden" name="operation" value="addR">
</form>';



// Форма редактирования 

if(is_array($rCurrent)) {
	foreach ($rCurrent as $r) {
		echo'
		<form action="relation.php" method="post">
			<div class="modal fade" id="edit'.$r['o2'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Edit Relation</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3 row">
							<label class="col-sm-4 col-form-label">Parent</label>
							<div class="col-sm-8">
								<input readonly type="text" class="form-control" value="'; echo $o[$r['o1']]['name']; echo'">
							</div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-4 col-form-label">Type</label>
							<div class="col-sm-8">
								<select name="ot" class="form-select">
									<option value="0" selected> </option>';
									foreach($ot as $Ot) {
										echo' <option '; if ($Ot['id'] == $o[$r['o2']]['ot']) {echo' selected=selected ';} echo' value="'; echo $Ot['id']; echo'">'; echo $Ot['name']; echo'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-4 col-form-label">Name</label>
							<div class="col-sm-8">
								<input name="o"  list="datalistOptions" type="text" class="form-control" value="'; echo $o[$r['o2']]['name']; echo'">
								<datalist id="datalistOptions">';
								foreach ($o as $O) {
									echo '<option value="'.$O['name'].'">';
								}
								echo '
								</datalist>
							</div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-4 col-form-label">Relation -></label>
							<div class="col-sm-8">
								<select name="rt2" class="form-select">
									<option value="0"> </option>';
									foreach($rt as $Rt) {
										echo' <option '; if ($Rt['id'] == $r['rt2']) {echo' selected=selected ';} echo' value="'; echo $Rt['id']; echo'">'; echo $Rt['name']; echo'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-4 col-form-label">Relation <-</label>
							<div class="col-sm-8">
								<select name="rt1" class="form-select">
									<option value="0"> </option>';
									foreach($rt as $Rt) {
										echo' <option '; if ($Rt['id'] == $r['rt1']) {echo' selected=selected ';} echo' value="'; echo $Rt['id']; echo'">'; echo $Rt['name']; echo'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-4 col-form-label">Start</label>
							<div class="col-sm-8">
								<input name="interval_start" type="date" class="form-control" value="">
							</div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-4 col-form-label">Stop</label>
							<div class="col-sm-8">
								<input name="interval_stop" type="date" class="form-control" value="">	
							</div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-4 col-form-label">Connect</label>
							<div class="col-sm-8">
								<select name="connect" class="form-select">
									<option value="0"> </option>';
									if(is_array($rCurrent)) {
										foreach ($rCurrent as $r2) {
											echo' <option value="'; echo $r2['o2']; echo'">'; echo $o[$r2['o2']]['otValue']; echo ' --> '; echo $o[$r2['o2']]['name']; echo'</option>';
										}
									}
									echo'
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Ok</button>
					</div>
				</div>
			  </div>
			</div>
			<input type="hidden" name="id" value="'.$r['id'].'">
			<input type="hidden" name="copy" value="'.$r['copy'].'">
			<input type="hidden" name="o2" value="'.$r['o2'].'">
			<input type="hidden" name="operation" value="edit">
		</form>';
	}
}


//Форма удаления
	
if(is_array($rCurrent)) {
	foreach ($rCurrent as $r) {
		echo '
		<form action="relation.php" method="post">
			<div class="modal fade" id="delete'.$r['o2'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Удаление</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
				  
						<p>Вы уверены, что хотите удалить?</p>
						
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Удалить</button>
					</div>
				</div>
			  </div>
			</div>
			<input type="hidden" name="id" value="'.$r['id'].'">
			<input type="hidden" name="copy" value="'.$r['copy'].'">
			<input type="hidden" name="operation" value="deleteR">
		</form>';
	}
}

//Форма Add relation type
	
echo '
<form action="relation.php" method="post">
	<div class="modal fade" id="addRt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Relation type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
		  
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Name</label>
					<div class="col-sm-8">
						<input name="rt" type="text" class="form-control">
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Ok</button>
			</div>
		</div>
	  </div>
	</div>
	<input type="hidden" name="operation" value="addRt">
</form>';


//Форма Add object type
	
echo '
<form action="relation.php" method="post">
	<div class="modal fade" id="addOt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Object type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label">Name</label>
					<div class="col-sm-8">
						<input name="et" type="text" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Ok</button>
			</div>
		</div>
	  </div>
	</div>
	<input type="hidden" name="operation" value="addEt">
</form>';


echo '
</div>';
?> 