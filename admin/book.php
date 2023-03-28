<?php

include '../database_connection.php';
include '../function.php';

if(!is_admin_login()){
	header('location:../admin_login.php');
}
$message = '';
$error = '';
if(isset($_POST["add_book"])){
	$formdata = array();
	if(empty($_POST["book_name"])){
		$error .= '<li>Book Name is required</li>';
	}
	else{
		$formdata['book_name'] = trim($_POST["book_name"]);
	}
	if(empty($_POST["book_category"])){
		$error .= '<li>Book Category is required</li>';
	}
	else{
		$formdata['book_category'] = trim($_POST["book_category"]);
	}
	if(empty($_POST["book_desc"])){
		$error .= '<li>Book desc is required</li>';
	}
	else{
		$formdata['book_desc'] = trim($_POST["book_desc"]);
	}	
	if(empty($_POST["book_price"])){
		$error .= '<li>Book price is required</li>';
	}
	else{
		$formdata['book_price'] = trim($_POST["book_price"]);
	}
	if(empty($_POST["book_author"])){
		$error .= '<li>Book Author is required</li>';
	}
	else{
		$formdata['book_author'] = trim($_POST["book_author"]);
	}
	if(!empty($_FILES['book_image']['name'])){
		$img_name = $_FILES['book_image']['name'];
		$img_type = $_FILES['book_image']['type'];
		$tmp_name = $_FILES['book_image']['tmp_name'];
		$fileinfo = @getimagesize($tmp_name);
		$width = $fileinfo[0];
		$height = $fileinfo[1];
		$image_size = $_FILES['book_image']['size'];
		$img_explode = explode(".", $img_name);
		$img_ext = strtolower(end($img_explode));
		$extensions = ["jpeg", "png", "jpg"];
		if(in_array($img_ext, $extensions)){
				$new_img_name = time() . '-' . rand() . '.' . $img_ext;
				if(move_uploaded_file($tmp_name, dirname(__DIR__).'/'."upload/" . $new_img_name)){
					$formdata['book_image'] = $new_img_name;
				}
		}
		else{
			$message .= '<li>Invalid Image File</li>';
		}
	}
	else{
		$message .= '<li>Please Select Profile Image</li>';
	}
	if($error == ''){
		$data = array(
			':book_category'		=>	$formdata['book_category'],
			':book_author'			=>	$formdata['book_author'],
			':book_name'			=>	$formdata['book_name'],
			':book_image'			=>	$formdata['book_image'],
			':book_desc'			=>	$formdata['book_desc'],
			':book_price'			=>	$formdata['book_price'],
		);
		$query = "INSERT INTO lms_book (book_category, book_author,  book_name, book_image,book_desc, book_price ) 
       	VALUES (:book_category, :book_author, :book_name,:book_image,:book_desc, :book_price)";
		$statement = $connect->prepare($query);
		$statement->execute($data);
		header('location:book.php?msg=add');
	}
}
if(isset($_POST["edit_book"])){
	$formdata = array();
	if(empty($_POST["book_name"])){
		$error .= '<li>Book Name is required</li>';
	}
	else{
		$formdata['book_name'] = trim($_POST["book_name"]);
	}
	if(empty($_POST["book_category"])){
		$error .= '<li>Book Category is required</li>';
	}
	else{
		$formdata['book_category'] = trim($_POST["book_category"]);
	}
	if(empty($_POST["book_author"])){
		$error .= '<li>Book Author is required</li>';
	}
	else{
		$formdata['book_author'] = trim($_POST["book_author"]);
	}
	if(empty($_POST["book_desc"])){
		$error .= '<li>Book desc is required</li>';
	}
	else{
		$formdata['book_desc'] = trim($_POST["book_desc"]);
	}	
	if(empty($_POST["book_price"])){
		$error .= '<li>Book price is required</li>';
	}
	else{
		$formdata['book_price'] = trim($_POST["book_price"]);
	}
	$formdata['book_image'] = $_POST['hidden_book_image'];
	if(!empty($_FILES['book_image']['name'])){
		$img_name = $_FILES['book_image']['name'];
		$img_type = $_FILES['book_image']['type'];
		$tmp_name = $_FILES['book_image']['tmp_name'];
		$fileinfo = @getimagesize($tmp_name);
		$width = $fileinfo[0];
		$height = $fileinfo[1];
		$image_size = $_FILES['book_image']['size'];
		$img_explode = explode(".", $img_name);
		$img_ext = strtolower(end($img_explode));
		$extensions = ["jpeg", "png", "jpg"];
		if(in_array($img_ext, $extensions)){
			$new_img_name = time() . '-' . rand() . '.'  . $img_ext;
				if(move_uploaded_file($tmp_name, dirname(__DIR__).'/'."upload/" . $new_img_name)){
					$formdata['book_image'] = $new_img_name;
				}		
		}
		else{
			$message .= '<li>Invalid Image File</li>';
		}
	}
	if($error == ''){
		$data = array(
			':book_category'		=>	$formdata['book_category'],
			':book_author'			=>	$formdata['book_author'],
			':book_name'			=>	$formdata['book_name'],
			':book_image'			=>	$formdata['book_image'],
			':book_desc'			=>	$formdata['book_desc'],
			':book_price'			=>	$formdata['book_price'],
			':book_id'				=>	$_POST["book_id"]
		);
		$query = "UPDATE lms_book 
        SET book_category = :book_category, 
        book_author = :book_author, 
        book_image = :book_image, 
        book_desc = :book_desc, 
        book_price = :book_price, 
        book_name = :book_name
        WHERE book_id = :book_id";
		$statement = $connect->prepare($query);
		$statement->execute($data);
		header('location:book.php?msg=edit');
	}
}

if(isset($_GET["action"], $_GET["code"]) && $_GET["action"] == 'delete'){
	$book_id = $_GET["code"];
	$data = array(
		':book_id'			=>	$book_id
	);
	$query = "DELETE FROM lms_book WHERE book_id = :book_id";
	$statement = $connect->prepare($query);
	$statement->execute($data);
	header('location:book.php');
}

$query = "SELECT * FROM lms_book ORDER BY book_id DESC";
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
	<h1>Book Management</h1>
	<?php 
	if(isset($_GET["action"])){
		if($_GET["action"] == 'add')
		{
	?>

	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="book.php">Book Management</a></li>
        <li class="breadcrumb-item active">Add Book</li>
    </ol>
    <?php 
    if($error != ''){
    	echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">'.$error.'</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    ?>
    <div class="card mb-4">
    	<div class="card-header">
    		<i class="fas fa-user-plus"></i> Add New Book
        </div>
        <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
			<div class="row">
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Title</label>
        					<input type="text" name="book_name" id="book_name" class="form-control" />
        				</div>
        			</div>
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Select Author</label>
        					<select name="book_author" id="book_author" class="form-control">
        						<?php echo fill_author($connect); ?>
        					</select>
        				</div>
        			</div>
        		
        		<div class="row">
        			<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Select Category</label>
        					<select name="book_category" id="book_category" class="form-control">
        						<?php echo fill_category($connect); ?>
        					</select>
        				</div>
        			</div>
					<div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Price</label>
        					<input type="text" name="book_price" id="book_price" class="form-control" />
        				</div>
        			</div>
        			
        		</div>
        		<div class="row">
        			
        			<div class="col-md-6">
        				<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="book_desc" id="book_desc" class="form-control"></textarea>
        				</div>
        			</div>
        		</div>
				<div class="row">
				<div class="col-md-6">
						<label class="form-label">Book Photo</label><br />
						<input type="file" name="book_image" id="book_image" />
					</div>
				</div>
        		<div class="mt-4 mb-3 text-center">
        			<input type="submit" name="add_book" class="btn btn-success" value="Add" />
        		</div>
			</div>
        	</form>
		
        </div>
    </div>

	<?php 
		}
		else if($_GET["action"] == 'edit'){
			$book_id = convert_data($_GET["code"], 'decrypt');
			if($book_id > 0){
				$query = "SELECT * FROM lms_book WHERE book_id = '$book_id'";
				$book_result = $connect->query($query);
				foreach($book_result as $book_row){
	?>
	<ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
		<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="book.php">Book Management</a></li>
        <li class="breadcrumb-item active">Edit Book</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
    		<i class="fas fa-user-plus"></i> Edit Book Details
       	</div>
       	<div class="card-body">
       		<form method="POST" enctype="multipart/form-data">
       			<div class="row">
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Title</label>
       						<input type="text" name="book_name" id="book_name" class="form-control" value="<?php echo $book_row['book_name']; ?>" />
       					</div>
       				</div>
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Select Author</label>
       						<select name="book_author" id="book_author" class="form-control">
       							<?php echo fill_author($connect); ?>
       						</select>
       					</div>
       				</div>
       			</div>
       			<div class="row">
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Select Category</label>
       						<select name="book_category" id="book_category" class="form-control">
       							<?php echo fill_category($connect); ?>
       						</select>
       					</div>
       				</div>
       				<div class="col-md-6">
					   <div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Price</label>
        					<input type="text" name="book_price" id="book_price" class="form-control" value="<?php echo $book_row['book_price']; ?>"/>
        				</div>
        			</div>
       				</div>
       			</div>
       			<div class="row">
        			
        			<div class="col-md-6">
        				<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="book_desc" id="book_desc" class="form-control"><?php echo $book_row['book_desc']; ?></textarea>
        				</div>
        			</div>
        		</div>
				<div class="row">
				<div class="col-md-6">
				<div class="mb-3">
						<label class="form-label">Book Photo</label><br />
						<input type="file" name="book_image" id="book_image" />
						<input type="hidden" name="hidden_book_image" value="<?php echo $user_row['book_image']; ?>" />
						<img src="<?php echo base_url() ?>upload/<?php echo $user_row['book_image']; ?>" width="100" class="img-thumbnail" />
					</div>
					</div>
				</div>
       			<div class="mt-4 mb-3 text-center">
       				<input type="hidden" name="book_id" value="<?php echo $book_row['book_id']; ?>" />
       				<input type="submit" name="edit_book" class="btn btn-primary" value="Edit" />
       			</div>
       		</form>
       		<script>
       			document.getElementById('book_author').value = "<?php echo $book_row['book_author']; ?>";
       			document.getElementById('book_category').value = "<?php echo $book_row['book_category']; ?>";
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
		<li class="breadcrumb-item active">Book Management</li>
	</ol>
	<?php 
	if(isset($_GET["msg"])){
		if($_GET["msg"] == 'add')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Book Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($_GET['msg'] == 'edit')
		{
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Book Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
	}
	?>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Book Management
                </div>
                <div class="col col-md-6" align="right">
                	<a href="book.php?action=add" class="btn btn-success btn-sm">Add</a>
                </div>
            </div>
        </div>
        <div class="card-body">
        	<table id="datatablesSimple">
        		<thead> 
        			<tr> 
        				<th>Title</th>
        				<th>Category</th>
        				<th>Author</th>
        				<th>Price</th>
        				<th>Image</th>
        				<th>Action</th>
        			</tr>
        		</thead>
        		<tfoot>
        			<tr>
        				<th>Title</th>
        				<th>Category</th>
        				<th>Author</th>
						<th>Price</th>
        				<th>Image</th>
        				<th>Action</th>
        			</tr>
        		</tfoot>
        		<tbody>
        		<?php 
        		if($statement->rowCount() > 0){
        			foreach($statement->fetchAll() as $row){
        				echo '
        				<tr>
        					<td>'.$row["book_name"].'</td>
        					<td>'.$row["book_category"].'</td>
        					<td>'.$row["book_author"].'</td>
        					<td>'.$row["book_price"].'</td>
        					<td><img src="../upload/'.$row["book_image"].'" class="img-thumbnail" width="75" /></td>
        					<td>
        						<a href="book.php?action=edit&code='.convert_data($row["book_id"]).'" class="btn btn-sm btn-primary">Edit</a>
        						<button type="button" name="delete_button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["book_id"].'`)">Delete</button>
        					</td>
        				</tr>
        				';
        			}
        		}
        		else{
        			echo '
        			<tr>
        				<td colspan="10" class="text-center">No Data Found</td>
        			</tr>
        			';
        		}
        		?>
        		</tbody>
        	</table>
        </div>
    </div>
    <script>
    	function delete_data(code){
    		if(confirm("Are you sure you want to delete this Category?")){
    			window.location.href = "book.php?action=delete&code="+code;
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



