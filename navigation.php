<?php
session_start();
require_once 'system-connection.php'; // подключаем базу данных
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);


$title = "Navigation";
echo '
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  
	<title>'; echo $title; echo'</title>
</head>';


// Сохраняем выбранные в форме навигатора значения

	if (isset($_POST['o1'])) { 
		$_SESSION['o1'] = $_POST['o1'];
		$_SESSION['o2'] = $_POST['o2'];
		$_SESSION['count'] = $_POST['count'];
	}

// Получаем сведения из базы данных

	$query ="SELECT * FROM `ot`";
	$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
	while($row = mysqli_fetch_array($result)){
		$ot[$row['id']]['id'] = $row['id'];
		$ot[$row['id']]['name'] = $row['name'];
	}

	$query ="SELECT * FROM `rt`";
	$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
	while($row = mysqli_fetch_array($result)){
		$rt[$row['id']]['id'] = $row['id'];
		$rt[$row['id']]['name'] = $row['name'];
	}

	$query ="SELECT * FROM `o` order by `ot`,`name`";
	$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
	while($row = mysqli_fetch_array($result)){
		$o[$row['id']]['id'] = $row['id'];
		$o[$row['id']]['name'] = $row['name'];
		$o[$row['id']]['ot'] = $row['ot'];
		$o[$row['id']]['otValue'] = $ot[$row['ot']]['name'];
		
		if ($row['ot']<3) {
			$oGeneral[$row['id']]['id'] = $row['id'];
			$oGeneral[$row['id']]['name'] = $row['name'];
		}
	}
	
	$query ="SELECT * FROM `r` order by `o1` ";
	$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($result));
	while($row = mysqli_fetch_array($result)) {
		
		$r[$row['id']]['id']=$row['id'];
		$r[$row['id']]['o1']=$row['o1'];
		$r[$row['id']]['o2']=$row['o2'];
		$r[$row['id']]['rt1']=$row['rt1'];
		$r[$row['id']]['rt2']=$row['rt2'];
		$r[$row['id']]['interval_start']=$row['interval_start'];
		$r[$row['id']]['interval_stop']=$row['interval_stop'];
		$r[$row['id']]['copy']=$row['copy'];
	}

// Убираем копии из массива R

	foreach ($oGeneral as $OG) {
		foreach ($oGeneral as $OG2) {
			if ($OG['name']==$OG2['name']) {
				if ($OG['id']<$OG2['id']) {
					
					foreach ($r as $R) {
						if ($R['o1'] == $OG2['id']) {
							$r[$R['id']]['o1'] = $OG['id'];
						}
						if ($R['o2'] == $OG2['id']) {
							$r[$R['id']]['o2'] = $OG['id'];
						}
					}
				}
			}
		}
	}

// Формируем список для формы выбора

	foreach ($r as $R) {
		$rIdList[] = $R['o1'];
	}
	$rIdList = array_unique($rIdList);



// Создаем массив NAVIGATION

	$navigation = array();
	$ii = 1;
	
	foreach ($r as $row) {
		if ($row['o1'] == $_SESSION['o1']) {
			$way = array();
			$way[] = $row['o1'];
			$way[] = $row['o2'];
			
			$driveway = array();
			$driveway[$row['o1']]['o1'] = $row['o1'];
			$driveway[$row['o1']]['o2'] = $row['o2'];
			if ($row['rt1']>0) {$rtName = $rt[$row['rt1']]['name'];} else {$rtName = "";}
			$driveway[$row['o1']]['rt'] = $rtName;

			$navigation[$ii]['id'] = $ii;
			$navigation[$ii]['o1'] = $row['o1'];
			$navigation[$ii]['o2'] = $row['o2'];
			$navigation[$ii]['way'] = $way;
			$navigation[$ii]['driveway'] = $driveway;
			$navigation[$ii]['count'] = count($way);
			
			$ii++;
		}
		
		$oParentChild[$row['o1']][] = $row['o2'];
	}


	// Внуки, правнуки и далее
 
	for ($i=2; $i<$_SESSION['count']; $i++) {
		include('system-navigation.php');
	}

// Проверяем массив systemNаvigation

	foreach($navigation as $row) {
		foreach($navigation as $row2) {
			if ($row['id']<>$row2['id']) {
				$driveway = serialize($row['driveway']);
				$driveway2 = serialize($row2['driveway']);
				
				// Убираем дубли
				
				if ($driveway == $driveway2) {
					unset($navigation[$row['id']]);
				}
				
				// Убираем позиции не относящиеся к выбранной паре
				
				if ($row['o1'] == $_SESSION['o1'] && $row['o2'] == $_SESSION['o2']) {} else {
					unset($navigation[$row['id']]);
				}
			}
		}
	} 



// Интерфейс Маршрута

	echo '<div class="container">';
	include('header.php');
		
	echo '
	<form action="navigation.php" method="post">
		<div class="input-group mb-3">
		  <span class="input-group-text"> From </span>
		  <select name="o1" class="form-select">
			<option selected>Выберите...</option>';
			foreach ($rIdList as $System_r) {
				echo '<option '; if ($System_r == $_SESSION['o1']) {echo ' selected=selected ';}  echo ' value="'.$System_r.'">'.$o[$System_r]['name'].'</option>';
			}
			echo'
		  </select>
		  <span class="input-group-text"> To </span>
		  <select name="o2" class="form-select">
			<option selected>Выберите...</option>';
			foreach ($rIdList as $System_r) {
				echo '<option '; if ($System_r == $_SESSION['o2']) {echo ' selected=selected ';}  echo ' value="'.$System_r.'">'.$o[$System_r]['name'].'</option>';
			}
			echo'
		  </select>
		  <span class="input-group-text"> Count </span>
		  <input name="count" class="form-control" type="number" value="'.$_SESSION['count'].'">
		  <button class="btn btn-outline-secondary" type="submit">Выбрать</button>
		</div>
	</form>

	<br><br>';
	 
	foreach ($navigation as $nav){
		
		echo '<div class="card text-dark bg-light mb-12">
				<div class="card-body">';
		
					$i=0;
					foreach ($nav['driveway'] as $driveway) {
						if ($i<1) {
							echo $o[$driveway['o1']]['name']; 
						}
						if ($o[$driveway['o1']]['ot'] == 2) {
							echo ' <--- <span class="badge bg-secondary">'; echo $driveway['rt'];  echo'</span> ';
						} else {
							echo ' <span class="badge bg-secondary">'; echo $driveway['rt'];  echo'</span> ---> ';
						}
						echo $o[$driveway['o2']]['name'];
						$i=$i+1;
					}
		echo ' </div></div><br><br>';
	} 



	echo '
	</div>';
?> 