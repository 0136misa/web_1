<?php

include '../database_connection.php';
include '../function.php';
if(!is_admin_login()){
	header('location:../admin_login.php');
}
$message = '';
$error = '';
if(isset($_POST["add_user"])){
	$formdata = array();
	if(empty($_POST["user_email_address"])){
		$message .= '<li>Email Address is required</li>';
	}
	else{
		if(!filter_var($_POST["user_email_address"], FILTER_VALIDATE_EMAIL)){
			$message .= '<li>Invalid Email Address</li>';
		}
		else{
			$formdata['user_email_address'] = trim($_POST['user_email_address']);
		}
	}
	if(empty($_POST["user_password"])){
		$message .= '<li>Password is required</li>';
	}
	else{
		$formdata['user_password'] = trim($_POST['user_password']);
	}
	if(empty($_POST['user_name'])){
		$message .= '<li>User Name is required</li>';
	}
	else{
		$formdata['user_name'] = trim($_POST['user_name']);
	}
	if(empty($_POST['user_address'])){
		$message .= '<li>User Address Detail is required</li>';
	}
	else{
		$formdata['user_address'] = trim($_POST['user_address']);
	}
	if(empty($_POST['user_contact_no'])){
		$message .= '<li>User Contact Number Detail is required</li>';
	}
	else{
		$formdata['user_contact_no'] = trim($_POST['user_contact_no']);
	}
	if(!empty($_FILES['user_profile']['name'])){
		$img_name = $_FILES['user_profile']['name'];
		$img_type = $_FILES['user_profile']['type'];
		$tmp_name = $_FILES['user_profile']['tmp_name'];
		$fileinfo = @getimagesize($tmp_name);
		$width = $fileinfo[0];
		$height = $fileinfo[1];
		$image_size = $_FILES['user_profile']['size'];
		$img_explode = explode(".", $img_name);
		$img_ext = strtolower(end($img_explode));
		$extensions = ["jpeg", "png", "jpg"];
		if(in_array($img_ext, $extensions)){
				$new_img_name = time() . '-' . rand() . '.' . $img_ext;
				if(move_uploaded_file($tmp_name, dirname(__DIR__).'/'."upload/" . $new_img_name)){
					$formdata['user_profile'] = $new_img_name;
				}	
		}
		else{
			$message .= '<li>Invalid Image File</li>';
		}
	}
	else{
		$message .= '<li>Please Select Profile Image</li>';
	}
	if($message == ''){
		$data = array(
			':user_email_address'		=>	$formdata['user_email_address']
		);
		$query = "SELECT * FROM lms_user WHERE user_email_address = :user_email_address";
		$statement = $connect->prepare($query);
		$statement->execute($data);
		if($statement->rowCount() > 0){
			$message = '<li>Email Already Register</li>';
		}
		else{
			$user_verificaton_code = md5(uniqid());
			$user_unique_id = 'VNPT-' . rand(10000000,99999999);
			$data = array(
				':user_name'			=>	$formdata['user_name'],
				':user_address'			=>	$formdata['user_address'],
				':user_contact_no'		=>	$formdata['user_contact_no'],
				':user_profile'			=>	$formdata['user_profile'],
				':user_email_address'	=>	$formdata['user_email_address'],
				':user_password'		=>	$formdata['user_password'],
				':user_unique_id'		=>	$user_unique_id,
			);
			$query = "INSERT INTO lms_user 
            (user_name, user_address, user_contact_no, user_profile, user_email_address, user_password ,user_unique_id) 
            VALUES (:user_name, :user_address, :user_contact_no, :user_profile, :user_email_address, :user_password,:user_unique_id)";
			$statement = $connect->prepare($query);
			$statement->execute($data);
			header('location:user.php?msg=add');
		}
	}
}

if(isset($_POST["edit_user"])){
	$formdata = array();
	if(empty($_POST["user_email_address"])){
		$message .= '<li>Email Address is required</li>';
	}
	else{
		if(!filter_var($_POST["user_email_address"], FILTER_VALIDATE_EMAIL)){
			$message .= '<li>Invalid Email Address</li>';
		}
		else{
			$formdata['user_email_address'] = trim($_POST['user_email_address']);
		}
	}
	if(empty($_POST["user_password"])){
		$message .= '<li>Password is required</li>';
	}
	else{
		$formdata['user_password'] = trim($_POST['user_password']);
	}
	if(empty($_POST['user_name'])){
		$message .= '<li>User Name is required</li>';
	}
	else{
		$formdata['user_name'] = trim($_POST['user_name']);
	}
	if(empty($_POST['user_address'])){
		$message .= '<li>User Address Detail is required</li>';
	}
	else{
		$formdata['user_address'] = trim($_POST['user_address']);
	}
	if(empty($_POST['user_contact_no'])){
		$message .= '<li>User Contact Number Detail is required</li>';
	}
	else{
		$formdata['user_contact_no'] = trim($_POST['user_contact_no']);
	}
	$formdata['user_profile'] = $_POST['hidden_user_profile'];
	if(!empty($_FILES['user_profile']['name'])){
		$img_name = $_FILES['user_profile']['name'];
		$img_type = $_FILES['user_profile']['type'];
		$tmp_name = $_FILES['user_profile']['tmp_name'];
		$fileinfo = @getimagesize($tmp_name);
		$width = $fileinfo[0];
		$height = $fileinfo[1];
		$image_size = $_FILES['user_profile']['size'];
		$img_explode = explode(".", $img_name);
		$img_ext = strtolower(end($img_explode));
		$extensions = ["jpeg", "png", "jpg"];
		if(in_array($img_ext, $extensions)){
				$new_img_name = time() . '-' . rand() . '.'  . $img_ext;
					if(move_uploaded_file($tmp_name, dirname(__DIR__).'/'."upload/" . $new_img_name)){
						$formdata['user_profile'] = $new_img_name;
					}	
		}
		else{
			$message .= '<li>Invalid Image File</li>';
		}
	}
	if($message == ''){
		$data = array(
			':user_name'			=>	$formdata['user_name'],
			':user_address'			=>	$formdata['user_address'],
			':user_contact_no'		=>	$formdata['user_contact_no'],
			':user_profile'			=>	$formdata['user_profile'],
			':user_email_address'	=>	$formdata['user_email_address'],
			':user_password'		=>	$formdata['user_password'],
			':user_id'				=>	$_POST["user_id"]
		);
		$query = "UPDATE lms_user
		SET user_name = :user_name, 
        user_address = :user_address, 
        user_contact_no = :user_contact_no, 
        user_profile = :user_profile, 
        user_email_address = :user_email_address ,
		user_password= :user_password
        WHERE user_id = :user_id";
		$statement = $connect->prepare($query);
		$statement->execute($data);
		header('location:user.php?msg=edit');
	}
}
if(isset($_GET["action"], $_GET['code']) && $_GET["action"] == 'delete'){
	$user_id = $_GET["code"];
	$data = array(
		':user_id'			=>	$user_id
	);
	$query = "DELETE FROM  lms_user WHERE user_id = :user_id";
	$statement = $connect->prepare($query);
	$statement->execute($data);
	header('location:user.php');
}
$query = "SELECT * FROM lms_user ORDER BY user_id DESC";
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
	<h1>User Management</h1>	
	<?php 
	if(isset($_GET["action"])){
		if($_GET["action"] == 'add')
		{
	?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="book.php">User Management</a></li>
        <li class="breadcrumb-item active">Add user</li>
    </ol>
    <?php 
    if($error != ''){
    	echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    ?>
    <div class="card mb-4">
    	<div class="card-header">
    		<i class="fas fa-user-plus"></i> Add New User
        </div>
        <div class="card-body">
        	<form method="POST" enctype="multipart/form-data">
        		<div class="row">
        			<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label">Email address</label>
						<input type="text" name="user_email_address" id="user_email_address" class="form-control" />
					</div>
        			</div>
        			<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label">Password</label>
						<input type="password" name="user_password" id="user_password" class="form-control" />
					</div>
        			</div>
        		</div>
        		<div class="row">
        			<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label">Username</label>
                        <input type="text" name="user_name" class="form-control" id="user_name" value="" />
                    </div>
        			</div>
        			
        		</div>
        		<div class="row">
        			
        			<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label">Contact</label>
						<input type="text" name="user_contact_no" id="user_contact_no" class="form-control" />
					</div>
        			</div>
        		</div>
				<div class="mb-3">
						<label class="form-label">Address</label>
						<textarea name="user_address" id="user_address" class="form-control"></textarea>
				</div>
				<div class="mb-3">
						<label class="form-label">Avatar</label><br />
						<input type="file" name="user_profile" id="user_profile" />
						<br />
						<span class="text-muted">Only .jpg & .png image allowed.</span>
					</div>
        		<div class="mt-4 mb-3 text-center">
					<span>
						<input type="submit" name="add_user" class="btn btn-success" value="Add" />
					</span>
        		</div>
        	</form>
        </div>
    </div>

	<?php 
		}
		else if($_GET["action"] == 'edit'){
			$user_id = convert_data($_GET["code"], 'decrypt');
			if($user_id > 0){
				$query = "SELECT * FROM lms_user WHERE user_id = '$user_id'";
				$user_result = $connect->query($query);
				foreach($user_result as $user_row){
	?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="book.php">Book Management</a></li>
        <li class="breadcrumb-item active">Edit user</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
    		<i class="fas fa-user-plus"></i> Edit user Details
       	</div>
       	<div class="card-body">
       		<form method="POST" enctype="multipart/form-data">
       			<div class="row">
       				<div class="col-md-6">
       				<div class="mb-3">
						<label class="form-label">Email address</label>
						<input type="text" name="user_email_address" id="user_email_address" class="form-control" value="<?php echo $user_row['user_email_address']; ?>" />
					</div>
       				</div>
       				<div class="col-md-6">
					   <div class="mb-3">
						<label class="form-label">Password</label>
						<input type="text" name="user_password" id="user_password" class="form-control" value="<?php echo $user_row['user_password']; ?>" />
					</div>
       				</div>
       			</div>
       			<div class="row">
       				<div class="col-md-6">
					   <div class="mb-3">
						<label class="form-label">Username</label>
                        <input type="text" name="user_name" class="form-control" id="user_name" value="<?php echo $user_row['user_name']; ?>" />
                    </div>
       				</div>
       				<div class="col-md-6">
					   <div class="mb-3">
						<label class="form-label">Contact</label>
						<input type="text" name="user_contact_no" id="user_contact_no" class="form-control" value="<?php echo $user_row['user_contact_no']; ?>"/>
					</div>
       				</div>
       			</div>
       			<div class="row">
       				<div class="col-md-6">
       					<div class="mb-3">
						<label class="form-label">Address</label>
						<textarea name="user_address" id="user_address" class="form-control">{<?php echo $user_row['user_address']; ?>}</textarea>
				</div>
       				</div>
       				<div class="col-md-6">
       					
					   <div class="mb-3">
						<label class="form-label">Avatar</label><br />
						<input type="file" name="user_profile" id="user_profile" />
						<br />
						<span class="text-muted">Only .jpg & .png image allowed.</span>
						<br />
						<input type="hidden" name="hidden_user_profile" value="<?php echo $user_row['user_profile']; ?>" />
						<img src="<?php echo base_url() ?>upload/<?php echo $user_row['user_profile']; ?>" width="100" class="img-thumbnail" />
					</div>
       				</div>
       			</div>
       			<div class="mt-4 mb-3 text-center">
       				<input type="hidden" name="user_id" value="<?php echo $user_row['user_id']; ?>" />
       				<input type="submit" name="edit_user" class="btn btn-primary" value="Edit" />
       			</div>
       		</form>
       		<script>
       			// 
       		</script>
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
        <li class="breadcrumb-item active">User Management</li>
		<li class="breadcrumb-item active">Add User</li>
    </ol>
    <?php 
 	if(isset($_GET["msg"])){
		if($_GET["msg"] == 'add'){
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New User Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'edit'){
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">User Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
 	}

    ?>
    <div class="card mb-4">
    	<div class="card-header">
    		<div class="row">
    			<div class="col col-md-6">
    				<i class="fas fa-table me-1"></i> User Management
    			</div>
    			<div class="col col-md-6" align="right">
				<a href="user.php?action=add" class="btn btn-success btn-sm">Add</a>

    			</div>
    		</div>
    	</div>
    	<div class="card-body">
    		<table id="datatablesSimple">
    			<thead>
    				<tr>
    					<th>Avatar</th>
                        <th>Username</th>
                        <th>Email Address</th>
                        <th>Password</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Action</th>
    				</tr>
    			</thead>
    			<tfoot>
    				<tr>
    					<th>Avatar</th>
                        <th>Username</th>
                        <th>Email Address</th>
                        <th>Password</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Action</th>
    				</tr>
    			</tfoot>
    			<tbody>
    			<?php 
    			if($statement->rowCount() > 0){
    				foreach($statement->fetchAll() as $row)
    				{
    					echo '
    					<tr>
    						<td><img src="../upload/'.$row["user_profile"].'" class="img-thumbnail" width="75" /></td>
   						<td>'.$row["user_name"].'</td>
    						<td>'.$row["user_email_address"].'</td>
    						<td>'.$row["user_password"].'</td>
    						<td>'.$row["user_contact_no"].'</td>
    						<td>'.$row["user_address"].'</td>
    						<td>
							<a href="user.php?action=edit&code='.convert_data($row["user_id"]).'" class="btn btn-sm btn-primary">Edit</a>
							<button type="button" name="delete_button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["user_id"].'`)">Delete</td>
    					</tr>
    					';
    				}
    			}
    			else{
    				echo '
    				<tr>
    					<td colspan="12" class="text-center">No Data Found</td>
    				</tr>
    				';
    			}
    			?>
    			</tbody>
    		</table>
    	</div>
    </div>
</div>
<script>
	function delete_data(code){
		if(confirm("Are you sure you want to delete this User?")){
			window.location.href = "user.php?action=delete&code="+code;
		}
	}
	<?php 
	}
    ?>

</script>
<?php 
	include '../footer.php';
?>