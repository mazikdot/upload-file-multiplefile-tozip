<?php
        if(isset($_POST['submitupload'])){
            
      
        date_default_timezone_set('asia/bangkok');
        $date = date('d-m-y h-i-s');
       if (count($_FILES["files"]['tmp_name']) > 1) {
        // Create a new zip archive
        
        $zip = new ZipArchive;
        $zipFileName = 'uploads/'.'-files-' . $date . '.zip';
        $zipSendDb = 'files-' . $date . '.zip';
        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
          // Add the uploaded files to the zip archive
          foreach ($_FILES["files"]['tmp_name'] as $key => $tmp_name) {
            $file_name = $date.$_FILES["files"]['name'][$key];
            $zip->addFile($tmp_name, $file_name);
          }
          // Close the zip archive
          $zip->close();
          echo "upload";
        }
      
        // Add the zip file to the $uploadedFiles array
        $uploadedFiles[] = array(
          'name' => $zipFileName,
          'type' => 'application/zip',
          'size' => filesize($zipFileName),
          'status' => 'success',
        );
      } else {
        // There is only one file, so process it as before
        foreach ($_FILES["files"]['tmp_name'] as $key => $tmp_name) {
          $errors = array();
          $file_name = $date.$_FILES["files"]['name'][$key];
          $file_size = $_FILES["files"]['size'][$key];
          $file_tmp = $_FILES["files"]['tmp_name'][$key];
          $file_type = $_FILES["files"]['type'][$key];
          if ($file_size > 7000000) {
            $errors[] = 'File size must be less than 7 MB';
          }
      
          $uploadedFile = array(
            'name' => $file_name,
            'type' => $file_type,
            'size' => $file_size,
            'status' => '',
          );
      
          if (empty($errors)) {
            if(
           !empty($_FILES["files"]['tmp_name'][$key])
            )
            {
              move_uploaded_file($file_tmp, "uploads/" . $file_name);
              $uploadedFile['status'] = 'success';
              echo "upload";
            } else {
                echo "folder null";
            }
          } else {
            $uploadedFile['status'] = 'error';
            $uploadedFile['errors'] = $errors;
          }
      
          $uploadedFiles[] = $uploadedFile;
        }
      }
    }
?>

<!-- HTML Form -->
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="files[]" multiple>
    <button type="submit" name="submitupload" class="btn btn-primary">Upload</button>
    
</form>
