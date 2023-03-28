<?php

include '../database_connection.php';
include '../function.php';
if(!is_admin_login()){
	header('location:../admin_login.php');
}
$message = '';
$error = '';

if(isset($_POST["add_author"])){
	$formdata = array();
	if(empty($_POST["author_name"])){
		$error .= '<li>Author Name is required</li>';
	}
	else{
		$formdata['author_name'] = trim($_POST["author_name"]);
	}
	if($error == ''){
		$query = "SELECT * FROM lms_author WHERE author_name = '".$formdata['author_name']."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		if($statement->rowCount() > 0){
			$error = '<li>Author Name Already Exists</li>';
		}
		else{
			$data = array(
				':author_name'			=>	$formdata['author_name']
			);
			$query = "INSERT INTO lms_author (author_name ) VALUES (:author_name)";
			$statement = $connect->prepare($query);
			$statement->execute($data);
			header('location:author.php?msg=add');
		}
	}
}
if(isset($_POST["edit_author"])){
	$formdata = array();
	if(empty($_POST["author_name"])){
		$error .= '<li>Author Name is required</li>';
	}
	else{
		$formdata['author_name'] = trim($_POST['author_name']);
	}
	if($error == ''){
		$author_id = convert_data($_POST['author_id'], 'decrypt');
		$query = "SELECT * FROM lms_author  WHERE author_name = '".$formdata['author_name']."' AND author_id != '".$author_id."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		if($statement->rowCount() > 0){
			$error = '<li>Author Name Already Exists</li>';
		}
		else{
			$data = array(
				':author_name'		=>	$formdata['author_name'],
				':author_id'		=>	$author_id
			);	

			$query = "UPDATE lms_author SET author_name = :author_nameWHERE author_id = :author_id";
			$statement = $connect->prepare($query);
			$statement->execute($data);
			header('location:author.php?msg=edit');
		}
	}
}

if(isset($_GET["action"], $_GET["code"]) && $_GET["action"] == 'delete'){
	$author_id = $_GET["code"];
	$data = array(
		':author_id'				=>	$author_id
	);
	$query = "DELETE FROM lms_author  WHERE author_id = :author_id";
	$statement = $connect->prepare($query);
	$statement->execute($data);
	header('location:author.php');
}
$query = "SELECT * FROM lms_author ORDER BY author_name ASC";
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
	<h1>Author Management</h1>
	<?php 

	if(isset($_GET["action"]))
	{
		if($_GET["action"] == "add")
		{
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="author.php">Author Management</a></li>
        <li class="breadcrumb-item active">Add Author</li>
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
    				<i class="fas fa-user-plus"></i> Add New Author
                </div>
                <div class="card-body">
                	<form method="post">
                		<div class="mb-3">
                			<label class="form-label">Author Name</label>
                			<input type="text" name="author_name" id="author_name" class="form-control" />
                		</div>
                		<div class="mt-4 mb-0">
                			<input type="submit" name="add_author" class="btn btn-success" value="Add" />
                		</div>
                	</form>
                </div>
            </div>
    	</div>
    </div>

	<?php 
		}
		else if($_GET["action"] == 'edit'){
			$author_id = convert_data($_GET["code"], 'decrypt');
			if($author_id > 0){
				$query = "SELECT * FROM lms_author WHERE author_id = '$author_id'";
				$author_result = $connect->query($query);
				foreach($author_result as $author_row){
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="author.php">Author Management</a></li>
        <li class="breadcrumb-item active">Edit Author</li>
    </ol>
    <div class="row">
    	<div class="col-md-6">
    		<div class="card mb-4">
    			<div class="card-header">
    				<i class="fas fa-user-edit"></i> Edit Author Details
    			</div>
    			<div class="card-body">
    				<form method="post">
    					<div class="mb-3">
    						<label class="form-label">Author Name</label>
    						<input type="text" name="author_name" id="author_name" class="form-control" value="<?php echo $author_row['author_name']; ?>" />
    					</div>
    					<div class="mt-4 mb-0">
    						<input type="hidden" name="author_id" value="<?php echo $_GET['code']; ?>" />
    						<input type="submit" name="edit_author" class="btn btn-primary" value="Edit" />
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
	else
	{

	?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
		<li class="breadcrumb-item active">Author Management</li>
	</ol>
	<?php 

	if(isset($_GET["msg"]))
	{
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Author Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Author Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}

	?>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Author Management
				</div>
				<div class="col col-md-6" align="right">
					<a href="author.php?action=add" class="btn btn-success btn-sm">Add</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="datatablesSimple">
				<thead>
					<tr>
						<th>Author Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Author Name</th>
						<th>Action</th>
					</tr>
				</tfoot>
				<tbody>
				<?php
				if($statement->rowCount() > 0)
				{
					foreach($statement->fetchAll() as $row)
					{
						echo '
						<tr>
							<td>'.$row["author_name"].'</td>
							<td>
								<a href="author.php?action=edit&code='.convert_data($row["author_id"]).'" class="btn btn-sm btn-primary">Edit</a>
								<button type="button" name="delete_button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["author_id"].'`)">Delete</button>
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
		</div>
	</div>
	<script>
		function delete_data(code)
		{
		
			if(confirm("Are you sure you want to delete this Author?"))
			{
				window.location.href = "author.php?action=delete&code="+code;
			}
		}
	</script>
	<?php 
	}
	?>
</div>
<?php 
	include '../footer.php';
?>