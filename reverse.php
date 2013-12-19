<?PHP
/** reverse.php will reverse the file and return the result
**/

include 'vars.php';

if (isset($_POST['revers'])) {
	
	//reset current file to the incoming file
	$current_file = trim($_POST['tempFile']);  //trim removes blank space?


	//create a new file that has the same name as the old file but subtract ".wav" then add time then add wav again
/**************QUESTION: why does it add 4 blank spaces? strlen stuff should be -4 to get ".wav" but instead -8 	¿¿why??  ******/
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	//$orig_file_name = substr($orig_file_name, 4, strlen($orig_file_name));	//chop off those spaces at the beginning of file name
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension

	$temp_file = $orig_file_name.time().$orig_file_ext;  //combine with timestamp
	$output = exec($sox." ".$ssh_dir.$current_file." ".$ssh_dir.$temp_file." reverse");	

	echo($temp_file);
	}

?>