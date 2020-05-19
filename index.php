<?php include("includes/init.php");
$title = "Gallery";
$gallery = "selected";

if ( isset($_GET['tag']) ) {
  $do_search = TRUE;
  $tag = filter_input(INPUT_GET, 'tag', FILTER_SANITIZE_STRING);
} else {
  array_push($messages, "Use search to find specific tags!");
  $do_search = FALSE;
  $tag = NULL;
}

// create new tag
if ( isset($_GET['create_tag']) ) {
  $do_create= TRUE;
  $create_tag = filter_input(INPUT_GET, 'create_tag', FILTER_SANITIZE_STRING);
  //check if tag already exists
  $check_tags = exec_sql_query($db, "SELECT * FROM tags", array());
  foreach ($check_tags as $check_tag) {
    if ($check_tag['tag']==$create_tag) {
      $do_create= FALSE;
    }
  }
  if ($do_create) {
    $sql= "INSERT INTO tags ('tag') VALUES ('$create_tag')";
    $tags = exec_sql_query($db, $sql, array());
  } else { ?>
    <p>This tag already exists.</p>
    <?php
  }
}

// deleting image
if ( isset($_POST['delete_image']) ) {
  $delete_image = $_POST['delete_image'];
  $delete_params = array(
    ':delete_image' => $delete_image
  );
  exec_sql_query($db, "DELETE FROM images WHERE id = :delete_image" , $delete_params);
  exec_sql_query($db, "DELETE FROM image_tags WHERE image_id LIKE :delete_image" , $delete_params);

  $delete_image_infos = exec_sql_query($db, "SELECT * FROM images WHERE id = :delete_image" , $delete_params);
  foreach($delete_image_infos as $delete_image_info){
    $delete_path = "uploads/images/".$delete_image_info["id"].".".$delete_image_info["file_ext"];
  }
  unlink($delete_path);
}

// function to display images
function display_images($records){
  foreach ($records as $record) {
    $id = htmlspecialchars($record["id"]);
    $file_ext = htmlspecialchars($record["file_ext"]);
    $photographer = htmlspecialchars($record["photographer"]);
    $img = "<img src = \"uploads/images/$id.$file_ext\"/>";
    $build = http_build_query(array("image_id" => $id));
    ?><div class="one-image"><?php
    echo "<a href=\"image.php?".$build ."\"> " . $img . "</a>";
    ?></div><?php
  }
}

// handle file upload
const MAX_FILE_SIZE = 1000000;
if (isset($_POST["submit_upload"])) {
  $upload_info = $_FILES["image_file"];
  $photographer_name = filter_input(INPUT_POST, 'image_photographer', FILTER_SANITIZE_STRING);
  if($upload_info['error'] == UPLOAD_ERR_OK){
    $valid = TRUE;
    if($upload_info['size'] < MAX_FILE_SIZE){
    $basename = basename($upload_info['name']);
    $upload_ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
    }
  }
  if($valid){
    $sql = "INSERT INTO images (file_name, file_ext, photographer) VALUES (:file_name, :file_ext, :photographer)";
     $params = array(
      ':file_name' => $basename,
      ':file_ext' => $upload_ext,
      ':photographer' => $photographer_name
    );
    exec_sql_query($db, $sql, $params);
    $id = $db ->lastInsertId("id");
    $new_path = "uploads/images/$id.$upload_ext";
    move_uploaded_file($_FILES["image_file"]["tmp_name"], $new_path);
    } else { ?>
    <p>To upload an image, choose a valid file.</p> <?php
  }
}

include("includes/head.php");
?>

<body>
  <?php include("includes/header.php"); ?>
  <!-- search by tag -->
  <div class = "search-form">
    <h3>Search for an image by tag:</h3>
    <form id="searchForm" action="index.php" method="get">
      <select name="tag">
      <option value="" selected disabled>Search:</option>
      <?php $search_tags = exec_sql_query($db, "SELECT * FROM tags", NULL);
      foreach ($search_tags as $search_tag) { ?>
        <option value="<?php echo $search_tag["tag"];?>"><?php echo htmlspecialchars($search_tag["tag"]);?></option>
        <?php
      } ?>
    </select>
    <button type="submit">Search</button>
  </form>
  </div>

  <!-- create new tag -->
  <div class = "create-tag-form">
    <h4>Or create your own tag here and then tag images with it!</h4>
    <form id="create_tagForm" action="index.php" method="get">
      <label for="create_tag">New tag:</label>
      <textarea id="create_tag" name="create_tag" cols="20" rows="1"></textarea>
      <button type="submit">Create</button>
    </form>
  </div>

  <?php
  if ($do_search) { ?>
    <div class = "show-tag"><h3>Showing Images with Tag: <?php echo htmlspecialchars($tag); ?></h3></div><?php
    //find tags id of searched tag
    $searched_tag_id= exec_sql_query($db, "SELECT DISTINCT * FROM tags WHERE tag IN ('".$tag."')", array());
    foreach ($searched_tag_id as $searched_id) {
      $search_id= $searched_id['id'];
    }
    //find image ids associated with that tag
    $search_image_ids= exec_sql_query($db, "SELECT DISTINCT * FROM image_tags WHERE tag_id IN ('".$search_id."')", array()); ?>
    <div class = "images">
      <?php foreach ($search_image_ids as $search_image_id) {
      $search_image= $search_image_id['image_id'];
      $records = exec_sql_query($db, "SELECT DISTINCT * FROM images WHERE id IN ('".$search_image."')", array());
      display_images($records);
    } ?>
    </div>
  <?php
  } else { ?>
    <div class="images">
      <?php $records = exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
      display_images($records); ?>
    </div>
  <?php
  } ?>

  <!-- image upload form -->
  <div class="upload-form">
    <h3>Upload an Image to the Potrait Photography Gallery</h3>
    <p> Images must be less than 1 MB.</p>

    <form id="uploadFile" action="index.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
      <div class="group_label_input">
        <label for="image_file">Upload File:</label>
        <input id="image_file" type="file" name="image_file">
      </div>
      <div class="group_label_input">
        <label for="image_photographer">Photographer:</label>
        <textarea id="image_photographer" name="image_photographer" cols="35" rows="2"></textarea>
      </div>
      <div class="group_label_input">
        <span></span>
        <div class = "submit">
        <button name="submit_upload" type="submit">Upload Image</button>
      </div>
    </form>
  </div>
</body>

<!-- footer -->
<div class = "footer-1">
  <?php include("includes/footer.php"); ?>
</div>
