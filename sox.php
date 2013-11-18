<?
/* This file will do all the Sox edits. Each function accepts a file name, an edit to perform, and variables

**/

include 'vars.php';



if (isset($_POST[change_file]) || isset($_POST[revers]) || isset($_POST[highpass]) || isset($_POST[lowpass]) || isset($_POST[speed_change_big]) || isset($_POST[speed_change_small]) || isset($_POST[undo])) {
	$current_file = $_POST[file_swap];
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension
	$undo_file = $orig_file_name."_undo".$orig_file_ext;
	//echo($_post[cfile]);
	} else {
	$current_file = $files_list[0];
	}

//if undo, then undo file becomes current file, and current file becomes the undo file (you only get one step of undo capability, in the future would be cool to make this an array)
//
//  (add code here) //

if (isset($_POST[undo])) {

	//create a new file that has the same name as the old file but subtract ".wav" then add time then add wav again
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension

//change the name of undo file to _temp
	exec("mv ".$ssh_dir."/".$undo_file." ".$ssh_dir."/".$orig_file_name."_temp".$orig_file_ext);

//change the name of current_file to _undo
	exec("mv ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$orig_file_name."_undo".$orig_file_ext);

//change the name of undo file (now known as "_temp") to current file's name
	exec("mv ".$ssh_dir."/".$orig_file_name."_temp".$orig_file_ext." ".$ssh_dir."/".$current_file);
}

if (isset($_POST[revers])) {
	
	//create a new file that has the same name as the old file but subtract ".wav" then add time then add wav again
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension
	$temp_file = $orig_file_name.time().$orig_file_ext;  //combine with timestamp
	$output = exec("/home/jasonsigal/soxtest/bin/sox ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$temp_file." reverse");
	//mv oldname newname   rename current file to undo file so the name is $orig_file_name."_undo".$orig_file_ext
	//echo($output."\n");
	exec("mv ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$orig_file_name."_undo".$orig_file_ext);
	//echo($output."\n");
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
	echo($current_file);
	}

if (isset($_POST[speed_change_small])) {
	$speed_change = $_POST[speed_amt_small];
	//echo("speed changed by ".$_POST[speed_amt_small]."%");
	$speed_change = $speed_change/100;
	echo("speed changed by ".$_POST[speed_amt_small]."%");

	//create a new file that has the same name as the old file but subtract ".wav" then add time then add wav again
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension
	$temp_file = $orig_file_name.time().$orig_file_ext;  //combine with timestamp
	$output = exec("/home/jasonsigal/soxtest/bin/sox ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$temp_file." speed ".$speed_change);
	//mv oldname newname   rename current file to undo file so the name is $orig_file_name."_undo".$orig_file_ext
	exec("mv ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$orig_file_name."_undo".$orig_file_ext);
	//rename temp file to current file
	exec("mv ".$ssh_dir."/".$temp_file." ".$ssh_dir."/".$current_file);
	$undo_file = $orig_file_name."_undo".$orig_file_ext;
	}

if (isset($_POST[speed_change_big])) {
	$speed_change = $_POST[speed_amt_big];
	echo("speed changed by ".$_POST[speed_amt_big]."%");
	
	$speed_change = $_POST[speed_amt_big]/100;
	//create a new file that has the same name as the old file but subtract ".wav" then add time then add wav again
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension
	$temp_file = $orig_file_name.time().$orig_file_ext;  //combine with timestamp
	$output = exec("/home/jasonsigal/soxtest/bin/sox ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$temp_file." speed ".$speed_change);
	//mv oldname newname   rename current file to undo file so the name is $orig_file_name."_undo".$orig_file_ext
	exec("mv ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$orig_file_name."_undo".$orig_file_ext);
	//rename temp file to current file
	exec("mv ".$ssh_dir."/".$temp_file." ".$ssh_dir."/".$current_file);
	$undo_file = $orig_file_name."_undo".$orig_file_ext;
	}

if (isset($_POST[lowpass])) {
	$freq = $_POST[freq];
	echo("Low Pass Filter Applied at ".$freq."Hz");
	
	//create a new file that has the same name as the old file but subtract ".wav" then add time then add wav again
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension
	$temp_file = $orig_file_name.time().$orig_file_ext;  //combine with timestamp
	$output = exec("/home/jasonsigal/soxtest/bin/sox ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$temp_file." lowpass ".$freq);
	//mv oldname newname   rename current file to undo file so the name is $orig_file_name."_undo".$orig_file_ext
	exec("mv ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$orig_file_name."_undo".$orig_file_ext);
	//rename temp file to current file
	exec("mv ".$ssh_dir."/".$temp_file." ".$ssh_dir."/".$current_file);
	$undo_file = $orig_file_name."_undo".$orig_file_ext;
	}

if (isset($_POST[highpass])) {
	$freq = $_POST[freq];
	echo("High Pass Filter Applied at ".$freq."Hz");
	
	//create a new file that has the same name as the old file but subtract ".wav" then add time then add wav again
	$orig_file_name = substr($current_file, 0, strlen($current_file) - 4); //extract original filename pre extension
	$orig_file_ext = substr($current_file,strlen($current_file)-4,4);  //extract original extension
	$temp_file = $orig_file_name.time().$orig_file_ext;  //combine with timestamp
	$output = exec("/home/jasonsigal/soxtest/bin/sox ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$temp_file." highpass ".$freq);
	//mv oldname newname   rename current file to undo file so the name is $orig_file_name."_undo".$orig_file_ext
	exec("mv ".$ssh_dir."/".$current_file." ".$ssh_dir."/".$orig_file_name."_undo".$orig_file_ext);
	//rename temp file to current file
	exec("mv ".$ssh_dir."/".$temp_file." ".$ssh_dir."/".$current_file);
	$undo_file = $orig_file_name."_undo".$orig_file_ext;
	}