<?php
include '../database_connection.php';
include '../function.php';

if(!is_admin_login()){
	header('location:../admin_login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Backup Source Code</title>
    <style>
        body {
	font-family: Arial, sans-serif;
	margin: 0;
	padding: 0;
	background-color: #f2f2f2;
}

h2 {
	font-size: 24px;
	margin: 30px 0 20px 0;
	text-align: center;
	color: #333;
}

form {
	width: 50%;
	margin: 0 auto;
	background-color: #fff;
	padding: 30px;
	border: 1px solid #ccc;
	border-radius: 5px;
	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

label {
	display: block;
	margin-bottom: 10px;
	color: #333;
}

input[type="text"] {
	width: 100%;
	padding: 10px;
	border: 1px solid #ccc;
	border-radius: 5px;
	font-size: 16px;
}

input[type="submit"] {
	background-color: #333;
	color: #fff;
	padding: 10px 20px;
	border: none;
	border-radius: 5px;
	font-size: 16px;
	cursor: pointer;
	transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
	background-color: #555;
}

p {
	text-align: center;
	color: #333;
	font-size: 18px;
	margin-top: 20px;
}
    </style>

</head>
<body>
	<h2>Backup Source Code</h2>
	<form method="post">
		<label>Backup file name:</label>
		<input type="text" name="backup_name" required><br><br>
		<input type="submit" value="Backup">
	</form>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$backup_name = $_POST["backup_name"];
	$source_dir = "../../*"; // Thư mục chứa source code
    var_dump($source_dir);
	$source_file = "source.tar.gz"; // Tên file zip sau khi nén
    if(!file_exists($source_file)){
        touch($source_file);
    }
	$backup_dir = "./backup/"; // Thư mục lưu trữ file backup
	$backup_file = $backup_dir . $backup_name . ".tar.gz"; // Tên file backup

	// Gọi lệnh nén source code
	$command = "tar -czvf " . $source_file . " " . $source_dir;
	shell_exec($command);

	// Di chuyển file zip đến thư mục backup
	if (file_exists($source_file)) {
		if (!file_exists($backup_dir)) {
			mkdir($backup_dir); // Tạo thư mục backup nếu chưa tồn tại
		}

		if (copy($source_file, $backup_file)) {
			echo "<p>Backup source code thành công.</p>";
		} else {
			echo "<p>Lỗi di chuyển file backup.</p>";
		}

	unlink($source_file); // Xóa file zip sau khi backup
	} else {
		echo "<p>Lỗi nén source code.</p>";
	}
}

?>

