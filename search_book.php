<?php

include 'database_connection.php';
include 'function.php';
if(!is_user_login()){
	header('location:user_login.php');
}
$query = "SELECT * FROM lms_book ORDER BY book_id DESC";
$statement = $connect->prepare($query);
$statement->execute();
$error='';
if(isset($_POST["add_comment"])){
	$formdata = array();
	$book_id = convert_data($_GET["code"], 'decrypt');
	if(empty($_POST["comment_content"])){
		$error .= '<li>Book Comment is required</li>';
	}
	else{
		$formdata['comment_content'] = trim($_POST["comment_content"]);
	}
	if($error==''){ 
		$user_unique_id= $_SESSION['user_id'];
		$query_user = "SELECT user_id FROM lms_user WHERE user_unique_id = '$user_unique_id'";
		$user = $connect->query($query_user);
		foreach( $user as $user_row){
			$data = array(
				':comment_content'	=>	$formdata['comment_content'],
			    ':book_id'			=>	$book_id,
				':user_id'			=>	$user_row['user_id'],
			);
		}
		$query = "INSERT INTO lms_comment (comment_content, book_id,  user_id ) VALUES (:comment_content, :book_id, :user_id)";
		$statement = $connect->prepare($query);
		$statement->execute($data);	
	 }
 }
 

include 'header.php';
?>
    <body>
    	<main>
    		<div class="container py-4">
    			<header class="pb-3 mb-4 border-bottom">
                    <div class="row">
        				<div class="col-md-6">
                            <a href="index.php" class="d-flex align-items-center text-dark text-decoration-none">
                                <span class="fs-4">LIBRARY SYSTEM</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <?php 
                            if(is_user_login()){
                            ?>
                            <ul class="list-inline mt-4 float-end">
                                <li class="list-inline-item"><?php echo $_SESSION['user_id']; ?></li>
                                <li class="list-inline-item"><a href="profile.php">Profile</a></li>
                                <li class="list-inline-item"><a href="logout.php">Logout</a></li>
                            </ul>
                            <?php 
                            }
                            ?>
                        </div>
                    </div>

    			</header>
<div class="container-fluid py-4" style="min-height: 700px;">
	<?php 
	if(isset($_GET["action"])){
		if($_GET["action"] == 'view'){
		$book_id = convert_data($_GET["code"], 'decrypt');
			if($book_id > 0){
				$query = "SELECT * FROM lms_book WHERE book_id = '$book_id'";
				$book_result = $connect->query($query);
				$query_comment = "SELECT * FROM lms_comment WHERE book_id = '$book_id'";
				$book_comment = $connect->query($query_comment);
				foreach($book_result as $book_row)
		{
	?>
    <div class="card mb-4">
    	<div class="card-header">
    		<i class="fas fa-user-plus"></i> Edit Book Details
       	</div>
       	<div class="card-body">
       		<div  >
       			<div class="row">
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Title:</label>
       						<p><?php echo $book_row['book_name']; ?> </p>
       					</div>
       				</div>
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Author:</label>
       						<p><?php echo $book_row['book_author']; ?></p>
       					</div>
       				</div>
       			</div>
       			<div class="row">
       				<div class="col-md-6">
       					<div class="mb-3">
       						<label class="form-label">Category:</label>
							   <p><?php echo $book_row['book_category']; ?></p>

       					</div>
       				</div>
       				<div class="col-md-6">
					   <div class="col-md-6">
        				<div class="mb-3">
        					<label class="form-label">Price:</label>
							<p><?php echo $book_row['book_price']; ?> USD</p>
        				</div>
        			</div>
       				</div>
       			</div>
       			<div class="row">
        			<div class="col-md-6">
        				<div class="mb-3">
						<label class="form-label">Description:</label>
						<p><?php echo $book_row['book_desc']; ?></p>
        				</div>
        			</div>
        		</div>
				<div class="row">
				<div class="col-md-6">
				<div class="mb-3">
						<label class="form-label">Book Photo</label><br />
						<img src="<?php echo "upload/".$book_row['book_image'] ?>" class="img-thumbnail"  />
					</div>
					</div>
				</div>
       			<div class="mt-4 mb-3 text-center">
       				<input type="hidden" name="book_id" value="<?php echo $book_row['book_id']; ?>" />
       				<a href="search_book.php" class="btn btn-primary " role="button">Back</a>
       			</div>
       		</div>
       	</div>
   	</div>
	<?php 
		foreach($book_comment as $comment_row){
			?>
	<div class="row">
		<div class="col-md-6">
	   <div class="card mb-4">
			<div class="card-body">
				<p><?php echo $comment_row['comment_content'] ?></p>
					<div class="d-flex justify-content-between">
					<div class="d-flex flex-row align-items-center">
						<img src="https://icon-library.com/images/avatar-icon-images/avatar-icon-images-4.jpg" alt="avatar" width="50"height="50" />
						<?php
						$user_id= $comment_row['user_id'];
						$query_user = "SELECT * FROM lms_user WHERE user_id = '$user_id'";
						$user = $connect->query($query_user);
						$user_name='';
						foreach($user as $user_row){
							$user_name=$user_row['user_name'];
						 }
						?>
						<p class="small mb-0 ms-2"><?php echo $user_name ?></p>
					</div>
					</div>
			</div>
			</div>
		</div>
	   </div>
	   <?php
				}
				?>
				<form method="POST">
					<label class="form-label">Your Comment:</label>
					<input type="text" name="comment_content" id="comment_content" class="form-control" />
					<input type="submit" name="add_comment" class="btn btn-primary" style="margin-top:20px;" value="add comment"></input>
				</form>
				<?php
			}
		}
	}
}
	else{
		?>
<div class="container-fluid py-4" style="min-height: 700px;">
	<h1>Search Book</h1>
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Book List
				</div>
				<div class="col col-md-6" align="right">

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
						<th>Action</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Title</th>
						<th>Category</th>
						<th>Author</th>
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
							<td>
								<a href="search_book.php?action=view&code='.convert_data($row["book_id"]).'" class="btn btn-sm btn-primary">View</a>
								
							</td>
							</tr>
						';
					}
				}
				else{
					echo '
					<tr>
						<td colspan="8" class="text-center">No Data Found</td>
					</tr>
					';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php }?>
</div>


<?php 
	include 'footer.php';
?>