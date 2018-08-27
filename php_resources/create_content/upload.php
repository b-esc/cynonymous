<?php
if (is_array($_FILES)) {
    if (is_uploaded_file($_FILES['targ_image']['tmp_name'])) {
        $sourcePath = $_FILES['targ_image']['tmp_name'];
        $targetPath = "images/" . $_FILES['targ_image']['name'];
        $chrisPath = "../../php_resources/create_content/images/" . $_FILES['targ_image']['name'];
        if (move_uploaded_file($sourcePath, $targetPath)) {
            ?>
<img class="image-preview" src="<?php echo $chrisPath; ?>" class="upload-preview" />
<?php
}
    }
}
?>