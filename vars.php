<?PHP
/*
This will include all of my variables, be sure to include vars.php in all files!!!!
*/

    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);
    error_reporting(E_ALL);



$dir    = 'example/media';
$files_list = scandir($dir);

/*variables for LOCAL */
$sox = '/usr/local/bin/sox ';
$uploaddir = '/Applications/MAMP/htdocs/audiotransformer/example/media';
$ssh_dir = '/Applications/MAMP/htdocs/audiotransformer/';
$uploaddir = '/Applications/MAMP/htdocs/audiotransformer/example/media';

/*variables for HOSTED
                $uploaddir = '/home/jasonsigal/jasonsigal.cc/webaudioeditor/example/media';
$sox = '/home/jasonsigal/soxtest/bin/sox';
$uploaddir = '/home/jasonsigal/jasonsigal.cc/webaudioeditor/example/media';
$ssh_dir = '/home/jasonsigal/jasonsigal.cc/webaudioeditor/';
*/

// function to reset the files list
function resetFiles($flist) {
//  $flist = scandir($dir);
    //unset the weird things in the folder that don't have audio extensions (<5 strlen) and then recreate the array without them.
    foreach ($flist as $key => $value) {
        if (strlen($value) < 5) {
            unset($flist[$key]);
            }
        }
    $flist = array_values($flist);  //ah, now that's a clean array!
    return $flist;
    }

$files_list = resetFiles($files_list);

//global variables that should be set every time page refreshes
$current_file; // = 'example/media/Thick_Business_-_Smoothest_Runes.wav'; //= $files_list[0];
$temp_file; // = null;
$old_file; // = null;
$orig_file_name; // = null;
$orig_file_ext; // = null;
$undo_file; // = null;

$undoIndex;
$undoList;

//print_r($files_list);
//echo("hey");
?>