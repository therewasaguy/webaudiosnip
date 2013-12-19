    <?PHP
include 'vars.php';




    // Limit what people can upload for security reasons
    $allowed_mime_types = array("video/3gpp"=>"3gp", 
                                "audio/x-wav"=>"wav", 
                                "audio/vnd.wave"=>"wav",
                                "audio/mp3"=>"mp3",
                                "audio/wav"=>"wav"
                                );

    // Make sure form was submitted
    if (isset($_FILES['bytes']))
    	//var_dump($_FILES);
    {
        // Check the mime type
        $allowed_types = array_keys($allowed_mime_types);
        $allowed = false;
        if (isset($_FILES['bytes']['type']))
        	//var_dump($_FILES);
        {       
            for ($i = 0; $i < sizeof($allowed_types) && !$allowed; $i++)
            {
                if (strstr($_FILES['bytes']['type'], $allowed_types[$i]))
                {
                    $allowed = true;
                }
            }
        
            // If the mime type is good, save it..
            if ($allowed)
            {
                // Create a name
                $uploadfilename = /**time() . "_" . rand(1000,9999) . "_" . **/basename($_FILES['bytes']['name']);
                
                $uploadfile = $uploaddir ."/". $uploadfilename;

//                $uploadrelativefile = 'http://jasonsigal.cc/webaudioeditor/example/media/' . $uploadfilename;
        
                $uploadrelativefile = 'example/media/' . $uploadfilename;
                if (move_uploaded_file($_FILES['bytes']['tmp_name'], $uploadfile))
                {
                    // Make sure the file isn't executable and you can delete it if you need
                    chmod($uploadfile, 0666);
                                        
                    // Tell the user
                    $current_file = $uploadfilename;

                    /** add this to a new undolistarray...but let's do it in JS instead!
                    unset($undoList);
                    $undoList = array($uploadrelativefile);
                    $undoIndex = 0;
                    **/

                    $files_list = scandir($dir);
 // disabling in upload.php but would enable otherwise /////// $files_list = resetFiles($files_list);                  
                    echo $uploadrelativefile;
                    

                }
                else
                {
                    echo "<p>Error on upload...!  Here is some debugging info:</p>";
                    //var_dump($_FILES);
                }
            }
            else
            {
                echo "<p>Type not allowed...! Here is some debugging info:</p>";
                //var_dump($_FILES);
            }
        }
//        else
//        {
//            echo "<p>Strange, file type not sent by browser...!  Here is some debugging info:</p>";
 //           var_dump($_FILES);
 //       }
    }


?>


