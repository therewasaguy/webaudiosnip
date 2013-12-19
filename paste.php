<?PHP

include 'vars.php';

if (isset($_POST['paste'])) {
	
	//reset current file to the incoming file
	$current_file = trim($_POST['currentFile']); 
	$edit_point = trim($_POST['ePoint']); //start edit
	$paste_file = trim($_POST['pasteFile']);  //paste file
	$file_len = trim($_POST['fileLen']); //file Length

	if ($edit_point <= 0) {
		$edit_point = 0.05;
	}

	if ($edit_point >= $file_len) {
		$edit_point = $file_len;
	}
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	//$orig_file_name = substr($orig_file_name, 4, strlen($orig_file_name));	//chop off those spaces at the beginning of file name
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension


//variables for preSnip, postSnip, copied, newfile
	$preSnip = $orig_file_name."prepp".rand(0,20).$orig_file_ext;
	$postSnip = $orig_file_name."postyy".rand(0,20).$orig_file_ext;
	$temp_file = $orig_file_name.rand(0,100).$orig_file_ext; //temporary file we'll delete
	$new_file = $orig_file_name.time().$orig_file_ext;  //combine with timestamp


	$output1 = exec($sox." ".$ssh_dir.$current_file." ".$ssh_dir.$preSnip." trim 0 ".$edit_point);	
	$output2 = exec($sox." ".$ssh_dir.$current_file." ".$ssh_dir.$postSnip." trim ".$edit_point." ".$file_len);	
	$output3 = exec($sox." ".$ssh_dir.$preSnip." ".$ssh_dir.$paste_file." ".$ssh_dir.$temp_file." splice ".$edit_point);			
	$output4 = exec($sox." ".$ssh_dir.$temp_file." ".$ssh_dir.$postSnip." ".$ssh_dir.$new_file." splice ");	


//make a json object with newfile and copied file
	header('Content-Type: application/json');
	echo json_encode(array('newFile' => $new_file));


//	echo($new_file);


//delete unecessary files
	$output5 = exec("rm ".$ssh_dir.$preSnip);
	$output6 = exec("rm ".$ssh_dir.$postSnip);
	$output6 = exec("rm ".$ssh_dir.$temp_file);	

}
?>