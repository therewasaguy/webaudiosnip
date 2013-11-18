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

	//mv oldname newname   rename current file to undo file so the name is $orig_file_name."_undo".$orig_file_ext
	//echo($output."\n");
	//exec("mv ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$orig_file_name."_undo".$orig_file_ext);


	//write old filename to text database of undo files
	//return new file

/*
	//mv oldname newname   rename current file to undo file so the name is $orig_file_name."_undo".$orig_file_ext
	//echo($output."\n");
	exec("mv ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$orig_file_name."_undo".$orig_file_ext);
	echo($output."\n");
	//rename temp file to current file
	exec("mv ".$ssh_dir."/".$temp_file." ".$ssh_dir."/".$current_file);
	//echo($output."\n");
	$undo_file = $orig_file_name."_undo".$orig_file_ext;
		/*
		echo("The Current File is ".$ssh_dir."/".$current_file);
		echo("\n");
		echo("The Undo File is ".$ssh_dir."/".$undo_file);

		echo($temp_file);
		echo($ssh_dir."/".$current_file);
		//echo($output);
		*/
//	echo("test1");
//	echo( $ssh_dir_sm."".$current_file." ".$ssh_dir_sm."1111".$temp_file." reverse");
//	echo(" current file: ".$current_file);
//	echo(" temp file: ".$temp_file);
//	echo("original file nom is ".$orig_file_name." ");
//	echo("original file ext is ".$orig_file_ext);
	echo($temp_file);
//	echo($orig_file_ext);
	}

?>