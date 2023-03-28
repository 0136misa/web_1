<?php

include '../database_connection.php';
include '../function.php';
if(!is_admin_login()){
	header('location:../admin_login.php');
}
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
<div class="container-fluid py-4">
	<h1 class="mb-5">Library System</h1>
	<img  style='width:100%; max-height:600px; object-fit:cover;' src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" class="img-fluid" alt="...">
	

<?php

include '../footer.php';

?>