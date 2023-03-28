<?php


include '../database_connection.php';
include '../function.php';
if(!is_admin_login()){
	header('location:../admin_login.php');
}
$message = '';
$error = '';
if(isset($_POST['edit_admin'])){
	$formdata = array();
	if(empty($_POST['admin_email'])){
		$error .= '<li>Email Address is required</li>';
	}
	else{
		if(!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL)){
			$error .= '<li>Invalid Email Address</li>';
		}
		else{
			$formdata['admin_email'] = $_POST['admin_email'];
		}
	}
	if(empty($_POST['admin_password'])){
		$error .= '<li>Password is required</li>';
	}
	else{
		$formdata['admin_password'] = $_POST['admin_password'];
	}
	if($error == ''){
		$admin_id = $_SESSION['admin_id'];
		$data = array(
			':admin_email'		=>	$formdata['admin_email'],
			':admin_password'	=>	$formdata['admin_password'],
			':admin_id'			=>	$admin_id
		);
		$query = "UPDATE lms_admin SET admin_email = :admin_email,admin_password = :admin_password WHERE admin_id = :admin_id";
		$statement = $connect->prepare($query);
		$statement->execute($data);
		$message = 'User Data Edited';
	}
}
$query = "SELECT * FROM lms_admin WHERE admin_id = '".$_SESSION["admin_id"]."'";
$result = $connect->query($query);

include '../header.php';
?>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
	<!-- Navbar Brand-->
	<a class="navbar-brand ps-3" href="index.php">Library System</a>
	<!-- Sidebar Toggle-->
	<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
	<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
	</form>
	<!-- Navbar-->
	<ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
			<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
				<li><a class="dropdown-item" href="profile.php">Profile</a></li>
				<li><a class="dropdown-item" href="logout.php">Logout</a></li>
			</ul>
		</li>
	</ul>
</nav>
<div id="layoutSidenav">
	<div id="layoutSidenav_nav">
		<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
			<div class="sb-sidenav-menu">
				<div class="nav">
					<a class="nav-link" href="category.php">Category</a>
					<a class="nav-link" href="author.php">Author</a>
					<a class="nav-link" href="book.php">Book</a>
					<a class="nav-link" href="user.php">User</a>
					<a class="nav-link" href="logout.php">Logout</a>
					<a class="nav-link" href="backup.php">Backup Source Code</a>

				</div>
			</div>
			<div class="sb-sidenav-footer">
			   
			</div>
		</nav>
	</div>
	<div id="layoutSidenav_content">
		<main>
<div class="container-fluid px-4">
	<h1 class="mt-4">Profile</h1>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Profile</a></li>
	</ol>
	<div class="row">
		<div class="col-md-6">
			<?php 
			if($error != ''){
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}

			if($message != ''){
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.$message.' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}
			?>
			<div class="card mb-4">
				<div class="card-header">
					<i class="fas fa-user-edit"></i> Edit Profile Details
				</div>
				<div class="card-body">
				<?php 
				foreach($result as $row){
				?>
					<form method="post">
						<div class="mb-3">
							<label class="form-label">Email Address</label>
							<input type="text" name="admin_email" id="admin_email" class="form-control" value="<?php echo $row['admin_email']; ?>" />
						</div>
						<div class="mb-3">
							<label class="form-label">Password</label>
							<input type="password" name="admin_password" id="admin_password" class="form-control" value="<?php echo $row['admin_password']; ?>" />
						</div>
						<div class="mt-4 mb-0">
							<input type="submit" name="edit_admin" class="btn btn-primary" value="Edit" />
						</div>
					</form>
				<?php 
				}
				?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	include '../footer.php';
?>