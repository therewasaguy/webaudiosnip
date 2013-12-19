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

                
 

                <div style="display:inline;width:300px;">
                <img src="img/audiotransformercooltext.png">
                    Now Editing: <span id="filenom"><nobr></nobr></span>  <em>Drag 'n' drop a new<i class="glyphicon glyphicon-music"></i>  file below</em>
            <button id="load-file" class="btn btn-sm">
            <i class="glyphicon glyphicon-cloud-upload"></i>
            Load File</button> 
            <button class="btn btn-sm" onClick="downloadFile()" align="center" style="margin:auto;">
                <i class="glyphicon glyphicon-download-alt"></i>
                Download
            </button>

                </div>
            </div>

            <div id="demo">


<!--drag and drop file! -->



                <div id="upload">
                    <div id="drop">
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

                    </div>
                </div>

                <div class="controls" id="buttons">
                    <!--
                    <button class="btn btn-primary" data-action="back">
                        <i class="glyphicon glyphicon-step-backward"></i>
                        Backward
                    </button>
-->
                    <button class="btn btn-primary" data-action="play">
                        <i class="glyphicon glyphicon-play"></i>
                        /
                        <i class="glyphicon glyphicon-pause"></i>
                        (Spacebar)
                    </button>

                    <button class="btn btn-primary" data-action="toggle-loop" style="width:75px;">
                        <span id="looped"><i class="glyphicon glyphicon-repeat"></i></span>
                        Loop
                    </button>
<!--
                    <button class="btn btn-primary" data-action="forth">
                        <i class="glyphicon glyphicon-step-forward"></i>
                        Forward
                    </button>

                    <button class="btn btn-primary" data-action="toggle-mute">
                        <i class="glyphicon glyphicon-volume-off"></i>
                        Toggle Mute
                    </button>
-->
<!--
                    <div class="mark-controls">
                        <button class="btn btn-success" data-action="green-mark">
                            <i class="glyphicon glyphicon-flag"></i>
                            Clear markers
                        </button>

                        <button class="btn btn-danger" data-action="red-mark">
                            <i class="glyphicon glyphicon-flag"></i>
                            Set mark
                        </button>
-->
                        <button class="btn" data-action="rev">
                            <i class="glyphicon glyphicon-retweet"></i>
                            Reverse
                        </button>

                        <button class="btn" data-action="speedUp">
                            <i class="glyphicon glyphicon-arrow-up"></i>
                            Speed Up
                        </button>

                        <button class="btn" data-action="speedDn">
                            <i class="glyphicon glyphicon-arrow-down"></i>
                            Slow Down
                        </button>

                        <button class="btn" data-action="reverb">
                            <i class="glyphicon glyphicon-tint"></i>
                            Reverb
                        </button>

                        <button class="btn" onclick="undo()">
                            <i class="glyphicon glyphicon-thumbs-down"></i>
                            Undo
                        </button>

                        <button class="btn" onclick="redo()">
                            <i class="glyphicon glyphicon-thumbs-up"></i>
                            Redo
                        </button>
</div>

<!--                        <button id="delete" disabled>Delete</button>-->
                        <audio id="preview" ></audio>
                        <div id="container" style="padding:1em 2em;"></div>


<!-- Button trigger modal 
                        <button class="btn" data-toggle="modal" data-target="#speedModal">
                            <i class="glyphicon glyphicon-resize-vertical"></i>
                          Change Speed
                        </button>

                        <!-- Modal -->
                        <!--
                        <div class="modal fade" id="speedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">Change Speed</h4>
                              </div>
                              <div class="modal-body">
<!--SLIDER: http://www.eyecon.ro/bootstrap-slider/ -->

<!--
            <script>$('.RC').slider()</script>
            <style>  #RC .slider-selection {
                                background: #FF8282;
                              }
                              #sl3 .slider-selection {
                                background: red;
                                }
                    #sl3 .slider .slider-horizontal {
                        width:300px;

                    }

  </style>
               <div class="well">
                <input type="text" value=".01" id="sl3" >
              </div>

                            </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Nevermind</button>
                                <button type="button" class="btn btn-primary" id="speedGoButton">Go!</button>
                                <script>
                                // console.log("NOT A TEST")
                                // $("#speedGoButton").click(setSpeed());
                                $("#speedGoButton").click(
                                    function(){
                                        setSpeed();
                                    }
                                )
                                </script>
                              </div>
                            </div>
                        --><!-- /.modal-content -->
                            <!--
                          </div> /.modal-dialog -->
                        <!-- </div>/.modal -->
<!-- dropdown no worky-->
<!--

                        <div class="btn-group">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            Effects <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li onclick="console.log('hey')"><a href="#">Change Speed</a></li>
                            <li><button class="btn" data-action"rev">Reverse</button></li>
                            <li><a href="#">Reverb</a></li>
                            <li class="divider"></li>
                            <li><a href="#">EQ</a></li>
                          </ul>
                        </div>
-->
<!--                    
                    </div>
                    -->
                </div>



            <div class="row marketing">
                <div class="col-lg-6">
                    <!--visualizations??-->
                    <canvas id="viz" style="background-color:#051315;">
                    </canvas>
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

<!--old upload placement
                <div id="upload" style="display:inline;height:20px;">
                    <div id="drop" style="width:870px;padding:5px;text-align:center;">
                <h4> Current File: <span id="filenom"><nobr></nobr></span>    <i class="glyphicon glyphicon-music"></i>   <em>Drag 'n' drop a new file here</em></h4>
                    </div>
                </div>
-->
            <hr />

<!-- olde download button
            <div class="row">
                <div class="downRow" style="height:70px;">
                        <a href="#">
                        <button class="btn" onClick="downloadFile()" align="center" style="margin:auto;">
                            <i class="glyphicon glyphicon-download-alt"></i>
                            Download File
                        </button>
                        </a<
                </div>
            </div>

-->
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
