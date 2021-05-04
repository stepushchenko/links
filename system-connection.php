<?php
mb_internal_encoding("UTF-8");

$host = 'localhost'; // адрес сервера 
$database = 'u1335934_db2'; // имя базы данных
$user = 'u1335934_default'; // имя пользователя
$password = 'u4lY!IRP'; // пароль
$connection = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($connection));
	
$connection->set_charset('utf8');
?> 