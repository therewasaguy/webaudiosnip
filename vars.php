<?PHP
/*
This will include all of my variables, be sure to include vars.php in all files!!!!
*/

    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);
    error_reporting(E_ALL);



//make an array of all files in audio directory, and the first is our current file
$dir    = 'example/media';
//$ssh_dir = '/home/jasonsigal/itp.jasonsigal.cc/webaudiosnip/example/media';
$ssh_dir = '/Applications/MAMP/htdocs/webaudiosnip/example/media';
$files_list = scandir($dir);

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
$current_file = $files_list[0];
$temp_file = null;
$old_file = null;
$orig_file_name = null;
$orig_file_ext = null;
$undo_file = null;

//print_r($files_list);
//echo("hey");