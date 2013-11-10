<!DOCTYPE html>

<?

//make an array of all files in audio directory, and the first is our current file
$dir    = 'example/media';
$ssh_dir = '/home/jasonsigal/itp.jasonsigal.cc/webaudiosnip/example/media';
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

print_r($files_list);

//if change file, then change the file. And if any of the other buttons are selected, also keep the file as selected in "file swap"
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
        /**
        echo("The Current File is ".$ssh_dir."/".$current_file);
        echo("\n");
        echo("The Undo File is ".$ssh_dir."/".$undo_file);

        echo($temp_file);
        echo($ssh_dir."/".$current_file);
        //echo($output);
        **/
    }
?>




<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Audio Transformer</title>

        <link href="data:image/gif;" rel="icon" type="image/x-icon" />

        <!-- Bootstrap -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

<!--        <link rel="stylesheet" href="http://simonwhitaker.github.com/github-fork-ribbon-css/gh-fork-ribbon.css" /> -->
        <link rel="stylesheet" href="example/css/style.css" />
        <link rel="screenshot" itemprop="screenshot" href="http://katspaugh.github.io/wavesurfer.js/example/screenshot.png" />

        <!-- wavesurfer.js        -->
         <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
         <script src="build/wavesurfer_unmin2.js"></script>


        <!-- Demo -->
        <script src="example/main.js"></script>
        <script src="example/trivia.js"></script>
    </head>

    <body itemscope itemtype="http://schema.org/WebApplication">
        <div class="container">
            <div class="header">
                <noindex>
                <ul class="nav nav-pills pull-right">
                    <li><a href="?fill">Fill</a></li>
                    <li><a href="?scroll">Scroll</a></li>
                    <li><a href="?normalize">Normalize</a></li>
                </ul>
                </noindex>

                <h1 itemprop="name">Audio Transformer</h1>
            </div>

            <div id="demo">
                <div id="waveform">
                    <div class="progress progress-striped active" id="progress-bar">
                        <div class="progress-bar progress-bar-info"></div>
                    </div>

                    <!-- Here be the waveform -->
                </div>

                <div class="controls">
                    <button class="btn btn-primary" data-action="back">
                        <i class="glyphicon glyphicon-step-backward"></i>
                        Backward
                    </button>

                    <button class="btn btn-primary" data-action="play">
                        <i class="glyphicon glyphicon-play"></i>
                        Play
                        /
                        <i class="glyphicon glyphicon-pause"></i>
                        Pause
                    </button>

                    <button class="btn btn-primary" data-action="forth">
                        <i class="glyphicon glyphicon-step-forward"></i>
                        Forward
                    </button>

                    <button class="btn btn-primary" data-action="toggle-mute">
                        <i class="glyphicon glyphicon-volume-off"></i>
                        Toggle Mute
                    </button>

                    <div class="mark-controls">
                        <button class="btn btn-success" data-action="green-mark">
                            <i class="glyphicon glyphicon-flag"></i>
                            Set green mark
                        </button>

                        <button class="btn btn-danger" data-action="red-mark">
                            <i class="glyphicon glyphicon-flag"></i>
                            Set red mark
                        </button>
                    </div>
                </div>

                <!--drag and drop file! -->
                <p class="lead pull-center" id="drop">
                    Drag'n'drop your favorite
                    <i class="glyphicon glyphicon-music"></i>-file
                    here!
                </p>
            </div>

            <div class="row marketing">
                <div class="col-lg-6">
                    <!--visualizations??-->
                </div>
            </div>

            <hr />

            <div class="row">
                <div class="col-lg-12">
                </div>
            </div>

            <div class="footer row">
                <div class="col-sm-12">
                    <a rel="license" href="http://creativecommons.org/licenses/by/3.0/deed.en_US"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/3.0/80x15.png" /></a>
                </div>

                <div class="col-sm-7">
                    Built by me using <span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/Text" property="dct:title" rel="dct:type">wavesurfer.js</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://github.com/katspaugh/wavesurfer.js" property="cc:attributionName" rel="cc:attributionURL">katspaugh</a> which is licensed under a&nbsp;<a style="white-space: nowrap" rel="license" href="http://creativecommons.org/licenses/by/3.0/deed.en_US">Creative Commons Attribution 3.0 Unported License</a>.
                </div>

                <div class="col-sm-5">
                    <div class="pull-right">
                        <noindex>
                        Demo music track is <a href="http://freemusicarchive.org/music/Thick_Business/Smoothest_Runes/Smoothest_Runes" rel="nofollow"><b>Smoothest Runes</b> <span class="muted">by</span>&nbsp;<b>Thick Business</b></a>. Thanks!
                        </noindex>
                    </div>
                </div>
            </div>
        </div>

<!--        <div class="github-fork-ribbon-wrapper right">
            <div class="github-fork-ribbon">
                <a itemprop="isBasedOnUrl" href="https://github.com/katspaugh/wavesurfer.js">Fork me on GitHub</a>
            </div>
        </div>
    -->
    </body>
</html>
