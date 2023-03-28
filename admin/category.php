<?php

include '../database_connection.php';
include '../function.php';
if(!is_admin_login()){
	header('location:../admin_login.php');
}
$message = '';
$error = '';
if(isset($_POST['add_category'])){
	$formdata = array();
	if(empty($_POST['category_name'])){
		$error .= '<li>Category Name is required</li>';
	}
	else{
		$formdata['category_name'] = trim($_POST['category_name']);
	}
	if($error == ''){
		$query = "SELECT * FROM lms_category WHERE category_name = '".$formdata['category_name']."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		if($statement->rowCount() > 0){
			$error = '<li>Category Name Already Exists</li>';
		}
		else{
			$data = array(
				':category_name'			=>	$formdata['category_name']	
			);

			$query = "INSERT INTO lms_category (category_name) VALUES (:category_name)";
			$statement = $connect->prepare($query);
			$statement->execute($data);
			header('location:category.php?msg=add');
		}
	}
}
if(isset($_POST["edit_category"])){
	$formdata = array();
	if(empty($_POST["category_name"])){
		$error .= '<li>Category Name is required</li>';
	}
	else{
		$formdata['category_name'] = $_POST['category_name'];
	}
	if($error == ''){
		$category_id = convert_data($_POST['category_id'], 'decrypt');
		$query = "SELECT * FROM lms_category WHERE category_name = '".$formdata['category_name']."' AND category_id != '".$category_id."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		if($statement->rowCount() > 0){
			$error = '<li>Category Name Already Exists</li>';
		}
		else{
			$data = array(
				':category_name'		=>	$formdata['category_name'],
				':category_id'			=>	$category_id
			);
			$query = "UPDATE lms_category SET category_name = :category_name WHERE category_id = :category_id";
			$statement = $connect->prepare($query);
			$statement->execute($data);
			header('location:category.php?msg=edit');
		}
	}
}

if(isset($_GET["action"], $_GET["code"]) && $_GET["action"] == 'delete'){
	$category_id = $_GET["code"];
	$data = array(
		':category_id'				=>	$category_id
	);
	$query = "DELETE FROM lms_category WHERE category_id = :category_id";
	$statement = $connect->prepare($query);
	$statement->execute($data);
	header('location:category.php');
}

$query = "SELECT * FROM lms_category ORDER BY category_name ASC";
$statement = $connect->prepare($query);
$statement->execute();

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

<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Category Management</h1>
	<?php 

	if(isset($_GET['action']))
	{
		if($_GET['action'] == 'add')
		{
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="category.php">Category Management</a></li>
		<li class="breadcrumb-item active">Add Category</li>
	</ol>
	<div class="row">
		<div class="col-md-6">
			<?php 
			if($error != ''){
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
			}
			?>
			<div class="card mb-4">
				<div class="card-header">
					<i class="fas fa-user-plus"></i> Add New Category
                </div>
                <div class="card-body">
                	<form method="POST">
                		<div class="mb-3">
                			<label class="form-label">Category Name</label>
                			<input type="text" name="category_name" id="category_name" class="form-control" />
                		</div>
                		<div class="mt-4 mb-0">
                			<input type="submit" name="add_category" value="Add" class="btn btn-success" />
                		</div>

                	</form>

                </div>
            </div>
		</div>
	</div>

	<?php 
		}
		else if($_GET["action"] == 'edit'){
			$category_id = convert_data($_GET["code"],'decrypt');
			if($category_id > 0){
				$query = "SELECT * FROM lms_category WHERE category_id = '$category_id'";
				$category_result = $connect->query($query);
				foreach($category_result as $category_row){
				?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="category.php">Category Management</a></li>
		<li class="breadcrumb-item active">Edit Category</li>
	</ol>
	<div class="row">
		<div class="col-md-6">
			<div class="card mb-4">
				<div class="card-header">
					<i class="fas fa-user-edit"></i> Edit Category Details
				</div>
				<div class="card-body">
					<form method="post">
						<div class="mb-3">
							<label class="form-label">Category Name</label>
							<input type="text" name="category_name" id="category_name" class="form-control" value="<?php echo $category_row['category_name']; ?>" />
						</div>
						<div class="mt-4 mb-0">
							<input type="hidden" name="category_id" value="<?php echo $_GET['code']; ?>" />
							<input type="submit" name="edit_category" class="btn btn-primary" value="Edit" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
				<?php 
				}
			}
		}
	}
	else{	
	?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Category Management</li>
	</ol>
	<?php 
	if(isset($_GET['msg'])){
		if($_GET['msg'] == 'add'){
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Category Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET["msg"] == 'edit'){
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Category Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}	
	?>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Category Management
				</div>
				<div class="col col-md-6" align="right">
					<a href="category.php?action=add" class="btn btn-success btn-sm">Add</a>
				</div>
			</div>
		</div>
		<div class="card-body">

			<table id="datatablesSimple">
				<thead>
					<tr>
						<th>Category Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Category Name</th>
						<th>Action</th>
					</tr>
				</tfoot>
				<tbody>
				<?php 
				if($statement->rowCount() > 0){
					foreach($statement->fetchAll() as $row){
						echo '
						<tr>
							<td>'.$row["category_name"].'</td>
							<td>
								<a href="category.php?action=edit&code='.convert_data($row["category_id"]).'" class="btn btn-sm btn-primary">Edit</a>
								<button name="delete_button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["category_id"].'`)">Delete</button>
							</td>
						</tr>
						';
					}
				}
				else
				{
					echo '
					<tr>
						<td colspan="4" class="text-center">No Data Found</td>
					</tr>
					';
				}
				?>
				</tbody>
			</table>
<script>
	function delete_data(code){
		if(confirm("Are you sure you want to delete this Category?")){
			window.location.href="category.php?action=delete&code="+code;
		}
	}
</script>
	</div>
</div>
	<?php 
	}
	?>
</div>
<?php 
	include '../footer.php';
?>