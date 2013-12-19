<!DOCTYPE html>

<!-- https://developer.mozilla.org/en-US/docs/Web/API/window.requestAnimationFrame -->

<!--assign everything that you upload a unique ID, pass into javascript-->

<?

include 'vars.php';


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
        <link rel="stylesheet" href="css/style.css" />
        <link rel="screenshot" itemprop="screenshot" href="http://katspaugh.github.io/wavesurfer.js/example/screenshot.png" />
        <link rel="stylesheet" href="css/slider.css" />
        <link rel="stylesheet" href="css/overwritten.css" />


         <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

        <!-- wavesurfer.js        -->
         <script src="js/wavesurfer_unmin2.js"></script>
         <!--boostrap-->
        <script src="css/bootstrap.js"></script>
        <script src="js/bootstrap-slider.js"></script>

        <!-- DAT GUI 
        <script src="js/dat.gui.js"></script>
    -->
        <!-- Main JS Stuff -->
        <script src="js/main.js"></script>

        <!--get audio contexts from wavesurfer
         <script src="js/audiocont.js"></script>
        -->
        <script src="js/trivia.js"></script>
                <script src="js/dropdown.js"></script>

    </head>

    <body itemscope itemtype="http://schema.org/WebApplication">


        <div class="container">
       <div id="transport">
            <!--RECORD STUFF-->
            <button id="record" class="btn btn-danger">Record</button> 
            <button id="stop" class="btn btn-warning" disabled>Stop</button> 
            <br /> <br />
        </div>
            <div class="header">
                <noindex>
                <ul class="nav nav-pills pull-right">
<!-- no worky
                    <li><a href="?fill">Fill</a></li>
                    <li><a href="?scroll">Scroll</a></li>
                    <li><a href="?normalize">Normalize</a></li>
-->
                </ul>
                </noindex>

                
 

                <div style="display:inline;width:300px;font-size:200%;">
                <img src="img/audiotransformercooltext.png">
                    Now Editing:                 <span id="filenom"> </span> 
                </div>


            <div id="demo">


<!--drag and drop file! -->



                <div id="upload">
                    <div id="drop">
<!--                                 <em><br />  Drag 'n' drop a new <i class="glyphicon glyphicon-music"></i>  file here!</em> -->
                    <canvas id="overlayTop" height="30px" width="804px" style="z-index: 0;"></canvas>
<!--                <h4> Current File: <span id="filenom"><nobr></nobr></span>    <i class="glyphicon glyphicon-music"></i>   <em>Drag 'n' drop a new file here</em></h4>
                <br />
            -->
                <div id="waveform">
                    <div class="progress progress-striped active" id="progress-bar">
                        <div class="progress-bar progress-bar-info"></div>
                    </div>

                    <!-- Here be the waveform -->
                </div>
                    <canvas id="overlay" height="30px" width="804px" style="z-index: 0;"></canvas>

<div id="controlbar" class="navbar navbar-inverse" style="text-align:center; padding:5px;">



    <button class="btn btn-success" data-action="play">
        <i class="glyphicon glyphicon-play"></i>
        /
        <i class="glyphicon glyphicon-pause"></i>
        (Spacebar)
    </button>

    <button class="btn btn-success navbar-btn" data-action="toggle-loop">
        <span id="looped"> Loop Mode <i class="glyphicon glyphicon-repeat"></i></span>
    </button>

    <li class="dropdown" style="display:inline;">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
        Edit 
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
          <li><a tabindex="-1" href="#" id="undo"><i class="glyphicon glyphicon-thumbs-down"></i>
            Undo (Z)</a></li>
          <li><a tabindex="-1" href="#" id="redo"><i class="glyphicon glyphicon-thumbs-up"></i>
            Redo (Y)</a></li>
          <li class="divider"></li>
          <li><a tabindex="-1" href="#" id="cut">Cut (x)</a></li>
          <li><a tabindex="-1" href="#" id="copy">Copy (c)</a></li>
          <li><a tabindex="-1" href="#" id="paste">Paste (v) </a></li>
          <li class="divider"></li>
          <li><a tabindex="-1" href="#" id="delete">Delete (del)</a></li>
        </ul>
    </li>



    <li class="dropdown" style="display:inline;">
        <button type="button" class="btn btn-primary dropdown-toggle pull-center" data-toggle="dropdown">
        Effects
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
          <li><a tabindex="-1" href="#" id="reverse"><i class="glyphicon glyphicon-retweet"></i> Backwards </a></li>
          <li><a tabindex="-1" href="#" id="speedup" onclick="speedUp()"><i class="glyphicon glyphicon-arrow-up"></i> Speed Up</a></li>
          <li><a tabindex="-1" href="#" id="slowdown" onclick="speedDn()"> <i class="glyphicon glyphicon-arrow-down"></i> Slow Down</a></li>
          <li><a tabindex="-1" href="#" id="reverb" onclick="reverb()"><i class="glyphicon glyphicon-tint"></i> Reverb</a></li> 

        <li class="divider"></li>
          <li><a tabindex="-1" href="#" id="volUp">Turn Up</a></li>
          <li><a tabindex="-1" href="#" id="volDn">Turn Down</a></li>
        </ul>
    </li>

                 <button class="btn btn-default navbar-btn" onClick="downloadFile()" align="center" style="margin:auto;">
                    <i class="glyphicon glyphicon-download-alt"></i> Download </button>
                <button id="upload-file" class="btn btn-default navbar-btn">
                    <i class="glyphicon glyphicon-upload"></i> Upload</button> 
                <button id="browse-samples" class="btn btn-default navbar-btn">
                    <i class="glyphicon glyphicon-cloud-upload"></i> Browse</button> 

<hr />
                    <canvas id="viz" style="background-color:#777;" height="100px" width="500px">
                    </canvas>
                    <canvas id="wViz" style="background-color:#777;" height="100px" width="250px">
                    </canvas>
</div>
</div>



                    </div>
                </div>

<!--                <div class="controls" id="buttons">-->


                        <audio id="preview" ></audio>
                        <div id="container" style="padding:1em 2em;"></div>
            <div class="row marketing">
                <div class="col-lg-6">
                    <!--visualizations??
                    <canvas id="viz" style="background-color:#051315;">
                    </canvas>-->
                    <script>
//                    console.log(WaveSurfer.getCurrentTime())
/*                      var canvas = document.querySelector('canvas');
                      var drawContext = canvas.getContext('2d');
                      canvas.width = 640;
                      canvas.height = 360;
*/
                    </script>
                </div>
            </div>

            <hr />

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

<script src="js/visualizer-sample.js"></script>

         <!--RecordRTC stuff-->
          <script src="js/RecordRTC.js"></script>
          <script src="js/recordxhr.js"></script>  

<script>


        $(function(){

        $('#sl1').slider({
          formater: function(value) {
            return 'Current value: '+value;
          }
        });
        $('#sl2').slider();

        var RGBChange = function() {
          $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
        };

        var r = $('#R').slider()
                .on('slide', RGBChange)
                .data('slider');
        var g = $('#G').slider()
                .on('slide', RGBChange)
                .data('slider');
        var b = $('#B').slider()
                .on('slide', RGBChange)
                .data('slider');

        $('#sl3').slider({
            min: .05,
            max: 200,
            step: .1,
            formater: function(value) {
            return 'Current value: '+value;
            }
        });

        $('#eg input').slider();
    });
  </script>


    </body>
</html>
