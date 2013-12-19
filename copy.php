<?PHP

include 'vars.php';

if (isset($_POST['copy'])) {
	
	//reset current file to the incoming file
	$current_file = trim($_POST['currentFile']); 
	$edit_start = trim($_POST['eStart']); //start edit
	$edit_stop = trim($_POST['eStop']); //stop edit
//	$file_len = trim($_POST['fileLen']); //file Length

	if ($edit_start <= 0) {
		$edit_start = 0.01;
	}

	if ($edit_stop >= $file_len) {
		$edit_stop = $file_len;
	}

	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	//$orig_file_name = substr($orig_file_name, 4, strlen($orig_file_name));	//chop off those spaces at the beginning of file name
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension


//variables for preSnip, postSnip, copied, newfile
	$preSnip = $orig_file_name."pre".$orig_file_ext;
	$postSnip = $orig_file_name."post".$orig_file_ext;
	$copied = $orig_file_name."c".rand(0,9).$orig_file_ext;
	$new_file = $orig_file_name.time().$orig_file_ext;  //combine with timestamp


//	$output1 = exec($sox." ".$ssh_dir.$current_file." ".$ssh_dir.$preSnip." trim 0 ".$edit_start);	
	$output2 = exec($sox." ".$ssh_dir.$current_file." ".$ssh_dir.$copied." trim ".$edit_start." ".$edit_stop);	
//	$output3 = exec($sox." ".$ssh_dir.$current_file." ".$ssh_dir.$postSnip." trim ".$edit_stop." ".$file_len);	
//	$output4 = exec($sox." ".$ssh_dir.$preSnip." ".$ssh_dir.$postSnip." ".$ssh_dir.$new_file." splice ".$edit_start);	

//make a json object with newfile and copied file
	header('Content-Type: application/json');
	echo json_encode(array('pasteFile' => $copied));


//	echo($new_file);

//delete unecessary files
	$output5 = exec("rm ".$ssh_dir.$preSnip);
	$output6 = exec("rm ".$ssh_dir.$postSnip);
}
?>