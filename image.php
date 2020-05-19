<?php include("includes/init.php");
$title = "Image Details";

// get image id
$params = array(
  ':image_id' => $_GET['image_id']
);
// adding tag
if (isset($_POST['add_tag'])) {
  $do_add= TRUE;
  $add_tag = $_POST['add_tag'];
  $add_tag_params = array (
    ':add_tag' => $add_tag
  );
  $get_add_tag_ids = exec_sql_query($db, "SELECT id FROM tags WHERE tag = :add_tag", $add_tag_params);
  foreach ($get_add_tag_ids as $get_add_tag_id) {
    $add_tag_id=$get_add_tag_id['id'];
  }
  $add_tag_id_params = array (
    ':add_tag_id' => $add_tag_id,
    ':image_id' => $_GET['image_id']
  );
  // check if tag exists
  $exist_tags = exec_sql_query($db, "SELECT * FROM image_tags WHERE tag_id = :add_tag_id AND image_id = :image_id", $add_tag_id_params);
  foreach ($exist_tags as $exist_tag) {
    if ($exist_tag['tag_id'] == $add_tag_id){
      $do_add = FALSE;
    }
  }
  if ($do_add) {
    exec_sql_query($db, "INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :add_tag_id)", $add_tag_id_params);
  } else {
    ?> <p>This tag already exists for this image.</p> <?php
  }
}

// deleting tag
if (isset($_POST['delete_tag'])){
  $delete_tag = $_POST['delete_tag'];
  $delete_tag_params = array (
    ':delete_tag' => $delete_tag
  );
  $get_delete_tag_ids = exec_sql_query($db, "SELECT id FROM tags WHERE tag = :delete_tag", $delete_tag_params);
  foreach ($get_delete_tag_ids as $get_delete_tag_id) {
    $delete_tag_id=$get_delete_tag_id['id'];
  }
  $delete_tag_id_params = array (
    ':delete_tag_id' => $delete_tag_id,
    ':image_id' => $_GET['image_id']
  );
  $sql = "DELETE FROM image_tags WHERE tag_id = :delete_tag_id AND image_id = :image_id";
  exec_sql_query($db, $sql, $delete_tag_id_params);
}

include("includes/head.php");?>

<body>
  <div class = "header-2">
    <?php include("includes/header.php"); ?>
  </div>
  <div class="single-image">
    <div>
      <?php $records = exec_sql_query($db, "SELECT * FROM images WHERE id = :image_id" , $params)->fetchAll(PDO::FETCH_ASSOC);
      foreach ($records as $record) {
        $id = htmlspecialchars($record["id"]);
        $file_name = htmlspecialchars($record["file_name"]);
        $file_ext = htmlspecialchars($record["file_ext"]);
        $photographer = htmlspecialchars($record["photographer"]);
        $img = "<img src = \"uploads/images/$id.$file_ext\">";
      }
      echo $img;
      $images_tag_ids = exec_sql_query($db, "SELECT tag_id FROM image_tags WHERE image_id = $id", array());
      ?>
    </div>

    <div class = "caption">
      <!-- image file and photographer info -->
      <div class = "image-name">
        <p><div><?php echo $file_name?></div></p>

        <!-- delete the image  -->
        <div class = "delete-button">
          <form id="delete_Form" action="index.php" method="post">
            <input type="hidden" name="delete_image" value="<?php echo $id;?>" />
            <!-- Source: https://image.flaticon.com/icons/svg/1345/1345823.svg -->
            <button type="submit"><img src = "images/trash.png" alt="Delete"/></button>
          </form>
        </div>
      </div>

      <p>Photographer: <?php echo $photographer?></p>

      <!-- display image tags -->
      <h3>Tags for this image:</h3>
      <?php foreach ($images_tag_ids as $images_tag_id) {
        $images_tags = exec_sql_query($db, "SELECT tag FROM tags WHERE id IN ('".$images_tag_id['tag_id']."')", array()) ->fetchAll(PDO::FETCH_ASSOC); ?>
      <div class="tags">
        <?php foreach ($images_tags as $images_tag) { ?>
        <div>
          <?php echo htmlspecialchars($images_tag['tag']); ?>
        </div>
        <!-- delete a tag from the image -->
        <div class = "delete-button">
          <?php $build = http_build_query(array("image_id" => $id));?>
          <form id="delete_tagForm" action="<?php echo "image.php?".$build .""?>" method="post">
            <input type="hidden" name="delete_tag" value="<?php echo $images_tag['tag'];?>" />
            <!-- Source: https://image.flaticon.com/icons/svg/1345/1345823.svg -->
            <button type="submit"><img src = "images/trash.png" alt="Delete"/></button>
          </form>
        </div>
        <?php
        } ?>
        </div>
      <?php
      }

      // add a tag to the image
      $build2 = http_build_query(array("image_id" => $id));?>
      <div class = "add-tag-form">
        <form id="add_tagForm" action="<?php echo "image.php?".$build2 .""?>" method="post">
          <select name="add_tag">
          <option value="" selected disabled>Add a tag:</option>
          <?php $search_tags = exec_sql_query($db, "SELECT tag FROM tags", array());
          foreach ($search_tags as $search_tag) { ?>
            <option value="<?php echo $search_tag['tag'];?>"><?php echo htmlspecialchars($search_tag['tag']);?></option>
            <?php
          } ?>
          </select>
          <button type="submit">Add</button>
        </form>
      </div>
    </div>
  </div>
</body>

<!-- footer -->
<div class="footer-2">
  <?php include("includes/footer.php"); ?>
</div>
