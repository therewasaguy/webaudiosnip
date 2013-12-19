'use strict';

// Create an instance
var wavesurfer = Object.create(WaveSurfer);

//adding global variables
var currentFile;
var timeMs;
var markerR;
var markerG;

//adding more for analysis
var context;
var freqDomain;
var analyser; //current analyser (we need to toggle between live (analyserR) and playback (analyserP) )
var analyserP; //analyser specific to the wavesurfer

//undo variables
var undoList = new Array();
var undoIndex = null;

var dragging = false;
var rectX;
var rectX2;
var drawCanvas2;
var rect;
var showRect;

// Init & load audio file
$(document).ready(function() {
    var options = {
        container     : document.querySelector('#waveform'),
        waveColor     : '#7FFF00',  //'#5A8790',
        progressColor : '#FF6E40',
        loaderColor   : 'purple',
        cursorColor   : 'navy',
        markerWidth   : 2
    };

    if (location.search.match('scroll')) {
        options.minPxPerSec = 100;
        options.scrollParent = true;
    }

    if (location.search.match('normalize')) {
        options.normalize = true;
    }

    /* Progress bar */
    var progressDiv = document.querySelector('#progress-bar');
    var progressBar = progressDiv.querySelector('.progress-bar');
    wavesurfer.on('loading', function (percent, xhr) {
        progressDiv.style.display = 'block';
        progressBar.style.width = percent + '%';
    });
    wavesurfer.on('ready', function () {
        progressDiv.style.display = 'none';
    });

//overlay canvas clearrect
    var overlay=document.getElementById("overlay");
    var overlayctx=overlay.getContext("2d");
    overlayctx.clearRect(0,0,20,40);


    // Init
    wavesurfer.init(options);
    // Load audio from URL
    //var currentTrack = 
    currentFile = 'example/media/beep2_angels.wav';
    var filenomm = currentFile.slice(14,currentFile.length);
    $('#filenom').text(" "+filenomm);
    undoIndex = 0;
    undoList[undoIndex] = currentFile;
    console.log("the current file is " + undoList)
    wavesurfer.load(currentFile);
    //<?php echo(json_encode($myVariable)); ?>;

    // Start listening to drag'n'drop on document
    wavesurfer.bindDragNDrop('#drop');

/********************    //frequency analysis stuff   *************/

    //audio context variable
    context = wavesurfer.backend.ac;

    var lowpass = wavesurfer.backend.ac.createBiquadFilter();
    wavesurfer.backend.setFilter(lowpass);

    //set up the analyzer
    analyserP = wavesurfer.backend.ac.createAnalyser();
    console.log(wavesurfer.backend);
    analyser = analyserP;
    wavesurfer.backend.gainNode.connect(analyser);
    analyser.connect(wavesurfer.backend.ac.destination);
    //get frequency domain
    freqDomain = new Float32Array(analyser.frequencyBinCount);
    analyser.getFloatFrequencyData(freqDomain);





/////////////////////////////////////////////////////////////////


    console.log("30000");


});

function setSpeed(){
    //see if this works
    console.log("setSpeed");
    var spd = $('#sl3').data('slider').getValue();
    console.log(spd);
}

function changePitch() {
  console.log(changePitch)
  }

function downloadFile() {
    console.log("download "+ currentFile);
    window.open(currentFile, '_blank');
}

//Recording stuff..

////////frequency stuff ///////


    function getFrequencyValue(frequency) {
      var nyquist = context.sampleRate/2;
      var index = Math.round(frequency/nyquist * freqDomain.length);
      return freqDomain[index];
    }

    function draw() {

      var canvas = document.getElementById('viz');
      var drawContext = canvas.getContext('2d');
      var WIDTH = 850;
      var HEIGHT = 100;
      canvas.width = WIDTH;
      canvas.height = HEIGHT;
      drawContext.font = "bold 12px sans-serif";
      drawContext.strokeStyle = "#fff";
      drawContext.fillStyle = "#fff";
      drawContext.textAlign = "center";
      drawContext.font = "12px sans-serif";
      drawContext.fillText("FREQUENCY", WIDTH/2, 14);
      drawContext.textAlign = "left";
      drawContext.fillText("20 Hz", 0, 14);
      drawContext.textAlign = "right";
      drawContext.fillText("20,000 Hz", WIDTH, 14);
      canvas.addEventListener("mousedown", mouseDownListener, false);


      //change which analyser to look at
      if (recording == true) {
        analyser = analyserR;
      } else {
        analyser = analyserP;
      }

        var freqDomain = new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteFrequencyData(freqDomain);
        for (var i = 0; i < analyser.frequencyBinCount; i++) {
          var value = freqDomain[i];
          var percent = value / 256;
          var height = HEIGHT * percent;
          var offset = HEIGHT - height - 1;
          var barWidth = WIDTH/analyser.frequencyBinCount;
          var hue = i/analyser.frequencyBinCount * 360;
          drawContext.fillStyle = 'hsl(' + hue + ', 100%, 50%)';
          drawContext.fillRect(i * barWidth, offset, barWidth, height);
        }
        
        var timeDomain = new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteTimeDomainData(freqDomain);
        for (var i = 0; i < analyser.frequencyBinCount; i++) {
          var value = timeDomain[i];
          var percent = value / 256;
          var height = HEIGHT * percent;
          var offset = HEIGHT - height - 1;
          var barWidth = WIDTH/analyser.frequencyBinCount;
          drawContext.fillStyle = 'black';
          drawContext.fillRect(i * barWidth, offset, 1, 1);
        }

// let's try adding drawing to the waveform canvas!
      var waveformCanvas = document.getElementById('canvas1');
      var drawContext2 = waveformCanvas.getContext('2d');

      //add a line across waveform
      drawContext2.strokeStyle="#fff000";
      drawContext2.moveTo(0, 128);
      drawContext2.lineTo(804, 128);
      drawContext2.stroke();
      drawContext2.strokeStyle = 'rgba(0, 0, 0, 0.0)';

// if selection is true, draw an opaque rectangle to highlight it
if (showRect == true && startPoint != endPoint && startPoint != 0 && endPoint != lenFile) {
  //console.log(startPoint + endPoint + "make a rectangel yo!");
  drawContext2.fillStyle = 'rgba(255, 255, 0, .01)';
  if (rectX2 > rectX) {
    drawContext2.fillRect(rectX, 0, rectX2-rectX, 256);
  } else {
    drawContext2.fillRect(rectX2, 0, rectX-rectX2, 256);
  }
    drawContext2.fillStyle = 'rgba(255, 255, 0, 0)';

} else {
  drawContext2.clearRect(0, 0, 870, 256);
  wavesurfer.drawBuffer();
}

//addingtimestamp Top
    var overlayTop=document.getElementById("overlayTop");
    var overlayTopctx=overlayTop.getContext("2d");
      overlayTopctx.clearRect(0,0,805,30);
//      overlayTopctx.fillStyle="rgba(255,255,255,1)";
  //    overlayTopctx.textAlign = "left";
    //  overlayTopctx.font = "12px Courier New"
//      overlayTopctx.fillText("(Drag files here)", 0, 12);
      overlayTopctx.fillStyle="rgba(255,255,0,1)";
      overlayTopctx.textAlign = "center";
      overlayTopctx.font = "bolder 18px Courier New"
    overlayTopctx.fillText("Current Time: "+timePos.toFixed(2) + "  Total: " + lenFile.toFixed(2) + " (Seconds)", 402, 22);
//      overlayctx.fillText("0", 300, 22);


//adding timestampBottom
    var overlay=document.getElementById("overlay");
    var overlayctx=overlay.getContext("2d");

//      waveformCanvas.width = waveformCanvas.width;
      overlayctx.fillStyle="rgba(255,255,0,1)";
      overlayctx.fillRect(0,0,805,30);
      overlayctx.strokeStyle = "#00";
      overlayctx.fillStyle = "#000";
      overlayctx.textAlign = "center";
      overlayctx.font = "bold 12px Courier New"
//      drawContext2.fillText(, 750, 20);
      if (!recording) {
      overlayctx.fillStyle="rgba(0,0,0,1)";
      overlayctx.moveTo(1, 0);
      overlayctx.lineTo(1, 5);
      overlayctx.stroke();
      overlayctx.fillText("0", 3, 22);
      overlayctx.moveTo(200, 0);
      overlayctx.lineTo(200, 5);
      overlayctx.stroke();
      overlayctx.fillText((200 / $("#canvas2").width() * lenFile).toFixed(2), 200, 22);
      overlayctx.moveTo(400, 0);
      overlayctx.lineTo(400, 5);
      overlayctx.stroke();
      overlayctx.fillText((400 / $("#canvas2").width() * lenFile).toFixed(2), 400, 22);
      overlayctx.moveTo(600, 0);
      overlayctx.lineTo(600, 5);
      overlayctx.stroke();
      overlayctx.fillText((600 / $("#canvas2").width() * lenFile).toFixed(2), 600, 22);
      overlayctx.moveTo(804, 0);
      overlayctx.lineTo(804, 5);
      overlayctx.fillText(1 * lenFile.toFixed(2), 790, 22);

//      overlayctx.fillText(((300 - $("#canvas2").offset().left) / $("#canvas2").width() * lenFile).toFixed(2)), 300, 22);
//      overlayctx.fillText("0", 300, 22);

//      overlayctx.fillText("Current Time: "+timePos.toFixed(2) + "  Total: " + lenFile.toFixed(2) + " (Seconds)" +  " |    Green Mark: " +markerG + "   Red Mark: " +markerR, 50, 22);
      // add event listener
      waveformCanvas.addEventListener("mousedown", mouseDownListener, false);

      var waveformCanvas2 = document.getElementById('canvas2');
      drawCanvas2 = waveformCanvas2.getContext('2d');
      waveformCanvas2.addEventListener("mousedown",mouseDownListener,false);


      waveformCanvas2.style.display="inline-block";
            var wave1 = document.getElementById('wave1');
      wave1.style.display="inline-block";



    } else if (recording) {
      var timeInMs = Date.now();
      var recTime = timeInMs - recStartTime;
//      recTime.toTimeString();
      drawContext2.fillStyle="#000";
      drawContext2.fillRect(0,0,870,256);
      drawContext2.fillStyle="rgba(255,255,0,1)";
      drawContext2.fillRect(100,50,670,156);
      drawContext2.strokeStyle = "#00";
      drawContext2.fillStyle = "#000";
      drawContext2.textAlign = "center";
      drawContext2.textBaseline="middle";
      drawContext2.font = "50px sans-serif"
      drawContext2.fillText("Recording: " + recTime + " ms", 435, 128);
      drawContext2.textBaseline="alphabetic";
      var waveformCanvas2 = document.getElementById('canvas2');
      waveformCanvas2.style.display="none";
      var wave1 = document.getElementById('wave1');
      wave1.style.display="none";
      overlayctx.clearRect(0,0,805,30);
      overlayTopctx.clearRect(0,0,805,30);
    }

/* draw rect selection
  drawCanvas2.fillStyle = "rgba(0, 0, 0, 0.1)";
  if (rectX2 > rectX) {
  rect =  drawCanvas2.fillRect(rectX2,0,rectX2 - rectX,400);
} else {
    rect =  drawCanvas2.fillRect(rectX2,0,rectX - rectX2,400);
}
wavesurfer.drawBuffer();
  //console.log(rectX + ", " + rectX2);
  */
    }

    setInterval(draw,1000/30); // 30 x per second



//////event listeners ////
function mouseDownListener(event) {
//  console.log("START");

if (!recording) { //only listen for clicks when not recording
  dragging = true;
//  rectX = 0;
  showRect = false;
  rectX = event.pageX - $("#canvas2").offset().left;
  if (dragging) {
      window.addEventListener("mousemove", mouseMoveListener, false);
    }
  wavesurfer.dragMark({
                id: 'start',
                mTime: event.pageX - $("#canvas2").offset().left,
                color: 'rgba(0, 255, 0, 0.5)'
            });
        rectX = event.pageX - $("#canvas1").offset().left;
        markerG = ((event.pageX - $("#canvas2").offset().left) / $("#canvas2").width() * lenFile).toFixed(2);
        window.addEventListener("mouseup", mouseUpListener,false);
      }

}

function mouseUpListener(event) {

  console.log("STOP");
  dragging = false;
  if (!dragging) {
        dragging = false;
        window.removeEventListener("mousemove", mouseMoveListener, false);
        window.removeEventListener("mouseup", mouseUpListener,false);
      }
  wavesurfer.dragMark({
                id: 'stop',
                mTime: event.pageX - $("#canvas2").offset().left,
                color: 'rgba(255, 0, 0, 0.5)'
            });
  rectX2 = event.pageX -  $("#canvas1").offset().left;
  markerR = ((event.pageX - $("#canvas2").offset().left) / $("#canvas2").width() * lenFile).toFixed(2);
  window.removeEventListener("mouseup", mouseUpListener,false);
}

function mouseMoveListener(event) {
  if (dragging) {
    showRect = true;
  }
  console.log("MOVING");
  rectX2 = event.pageX - $("#canvas1").offset().left;
}

//trying to take the function down here...so it's not in document.ready
function processFileUpload(droppedFiles) {
         // add your files to the regular upload form
    var uploadFormData = new FormData();
    //console.log(droppedFiles);
    uploadFormData.append("bytes", droppedFiles);
    uploadFormData.append("form_submitted",true);
    //console.log(droppedFiles.length); 
//    if(droppedFiles.length > 0) { // checks if any files were dropped
//        for(f = 0; f < droppedFiles.length; f++) { // for-loop for each file dropped
//            uploadFormData.append("bytes",droppedFiles[f]);   // adding every file to the form so you could upload multiple files
 //           uploadFormData.append("form_submitted",true);  //let's try this?
            //console.log(uploadFormData);
 //       }
//        uploadFormData.append()
    //}
        //console.log(uploadFormData);
    

 // the final ajax call
    //console.log("now the form data is"+uploadFormData);
      $.ajax({
        url : escape("upload.php"), // escape to get rid of bogus numbers
        type : "POST",
        data : uploadFormData,
        cache : false,
        contentType : false,
        processData : false,
        success : function(ret) {
                 // callback function
                 //console.log(ret);
                 //var filePath = "example/media/";
                 //currentFile = filePath.concat(ret);
                 currentFile = $.trim(ret);     //remove white space!
                 undoIndex = 0;
                 undoList[undoIndex] = currentFile;
                 console.log("the current file is " + undoList)
                 wavesurfer.load(currentFile)
                 //write the name of the file to div id = filenom
                 //wavesurfer load that file
                 //set the current file
        }
       });

    };


    //simulate a click when drop is selected ?
    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

// Play at once when ready
// Won't work on iOS until you touch the page
wavesurfer.on('ready', function () {
    //wavesurfer.play();
});


//show and hide buttons

function greyButtons() {
  var controls = document.getElementById('buttons');
  controls.style.display = "none";
}

function reviveButtons() {
  var controls = document.getElementById('buttons');
  controls.style.display = "block";
}


///////////////********** UNDO / REDO ***********/

function fileChange() {
  undoIndex = undoIndex+1;
  undoList[undoIndex] = currentFile;
  console.log("undo index is: " + undoIndex +" undoList is "+ undoList);
  //$('#filenom').text(currentFile);
  wavesurfer.load(currentFile);
 var filenomm = currentFile.slice(14,45);
if (currentFile.length > 46) {
  filenomm = filenomm.concat("...");
}
$('#filenom').text(" ::  "+filenomm);
}

function undo() {
  console.log("undo");
  if (undoIndex > 0) {
    undoIndex = undoIndex - 1;
    currentFile = undoList[undoIndex];
    console.log("undo index is: " + undoIndex +" undoList spot is "+ undoList[undoIndex]);
    wavesurfer.load(currentFile);
  }
  else {
    alert("No More To Undo!");
  }
};

function redo() {
  console.log("redo");
  if (undoIndex < undoList.length - 1) {
    undoIndex = undoIndex + 1;
    currentFile = undoList[undoIndex];
    console.log("undo index is: " + undoIndex +" undoList spot is "+ undoList[undoIndex]);
    wavesurfer.load(currentFile);
  } else {
    alert("No More To Redo!");
  }
};



// Bind buttons and keypresses
(function () {
    var eventHandlers = {
        'play': function () {
            wavesurfer.playPause();
            //get FFT
            console.log(getFrequencyValue(1000));

        },

        'green-mark': function () {
            wavesurfer.mark({
                id: 'up',
                color: 'rgba(0, 255, 0, 0.5)'
            });
        },

        'red-mark': function () {
            wavesurfer.mark({
                id: 'down',
                color: 'rgba(255, 0, 0, 0.5)'
            });
        },

        'back': function () {
            wavesurfer.skipBackward();
        },

        'forth': function () {
            wavesurfer.skipForward();
        },

        'toggle-mute': function () {
            wavesurfer.toggleMute();
        },

        'toggle-loop': function () {
            wavesurfer.toggleLooping();
//            console.log("loop?");
            //
        },

        'rev': function() {  //REVERSE
            //0. prepare the infos
            var uploadFormData = new FormData();
            //console.log(droppedFiles);
            uploadFormData.append("tempFile", currentFile);
            uploadFormData.append("revers", true);
            greyButtons();

            // 1. tell PHP to reverse the current file
              $.ajax({
                url : escape("reverse.php"), // use your target
                type : "POST",
                data : uploadFormData,
                cache : false,
                contentType : false,
                processData : false,
                success : function(ret) { // callback function
                         currentFile = $.trim(ret);
                         fileChange();
                }
               })
          },


        'reverb': function() {
            console.log("Reverb");

            var uploadFormData = new FormData();
            uploadFormData.append("tempFile", currentFile);
            uploadFormData.append("reverb", true);
            greyButtons();

              $.ajax({
                url : escape("reverb.php"),
                type : "POST",
                data : uploadFormData,
                cache : false,
                contentType : false,
                processData : false,
                success : function(ret) {
                         currentFile = $.trim(ret);
                         fileChange();
                }
               })
          },

        'speedUp': function() {
            console.log("speedUp");
            greyButtons();

            //0. prepare the infos
            var uploadFormData = new FormData();
            //console.log(droppedFiles);
            uploadFormData.append("tempFile", currentFile);
            uploadFormData.append("speed", true);
            uploadFormData.append("amount", 1.5);


            // 1. tell PHP to reverse the current file
              $.ajax({
                url : escape("speed.php"), // use your target
                type : "POST",
                data : uploadFormData,
                cache : false,
                contentType : false,
                processData : false,
                success : function(ret) {
                         // callback function
                         //console.log(ret);
                         currentFile = $.trim(ret);
                         fileChange();
                }
               })
          },
            
            'speedDn': function() {
            console.log("slowDn");
            greyButtons();

            //0. prepare the infos
            var uploadFormData = new FormData();
            //console.log(droppedFiles);
            uploadFormData.append("tempFile", currentFile);
            uploadFormData.append("speed", true);
            uploadFormData.append("amount", .7);


            // 1. tell PHP to reverse the current file
              $.ajax({
                url : escape("speed.php"), // use your target
                type : "POST",
                data : uploadFormData,
                cache : false,
                contentType : false,
                processData : false,
                success : function(ret) {
                         // callback function
                         //console.log(ret);
                         currentFile = $.trim(ret);
                         fileChange();
                }
               });


        }
    };

    //map keys!
    document.addEventListener('keydown', function (e) {
        var map = {
            32: 'play',       // space
            38: 'green-mark', // up
            40: 'red-mark',   // down
            37: 'back',       // left
            39: 'forth'       // right

        };
        if (e.keyCode in map) {
            var handler = eventHandlers[map[e.keyCode]];
            e.preventDefault();
            handler && handler(e);
        }
    });

    document.addEventListener('click', function (e) {
        var action = e.target.dataset && e.target.dataset.action;
        if (action && action in eventHandlers) {
            eventHandlers[action](e);
        }
    });
}());

// Flash mark when it's played over
wavesurfer.on('mark', function (marker) {
    if (marker.timer) { return; }

    marker.timer = setTimeout(function () {
        var origColor = marker.color;
        marker.update({ color: 'yellow' });

        setTimeout(function () {
            marker.update({ color: origColor });
            delete marker.timer;
        }, 100);
    }, 100);
});

wavesurfer.on('error', function (err) {
    console.error(err);
});
