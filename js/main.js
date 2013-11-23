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
var analyser;

//undo variables
var undoList = new Array();
var undoIndex = null;

// Init & load audio file
$(document).ready(function() {
    var options = {
        container     : document.querySelector('#waveform'),
        waveColor     : 'violet',
        progressColor : 'purple',
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

    // Init
    wavesurfer.init(options);
    // Load audio from URL
    //var currentTrack = 
    currentFile = 'example/media/beep2_angels.wav';
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
    analyser = wavesurfer.backend.ac.createAnalyser();
    console.log(wavesurfer.backend);
    wavesurfer.backend.gainNode.connect(analyser);
    analyser.connect(wavesurfer.backend.ac.destination);
    //get frequency domain
    freqDomain = new Float32Array(analyser.frequencyBinCount);
    analyser.getFloatFrequencyData(freqDomain);





/////////////////////////////////////////////////////////////////

    console.log("sup?");
    // NEW upload stuff via http://stackoverflow.com/questions/14835005/drag-drop-file-upload
    //http://api.jquery.com/jQuery.post/
/*    $("#drop").bind("drop", function(e) {
        console.log("start");
    files = e.dataTransfer.files[0];
    processFileUpload(files); 
    console.log("stop");
    // forward the file object to your ajax upload method
    return false;
    });
*/

//GUI better to put this in the same place as the other onloads
  var text = new myEffect();
  var gui = new dat.GUI();
  gui.add(text, 'changePitch', -5, 5).onChange(changePitch);
  gui.add(text, 'speed', -5, 5);
  gui.add(text, 'typeOfSpeed', [ 'pizza', 'chrome', 'hooray' ] );


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

    }

    setInterval(draw,1000/30); // 30 x per second



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


///////////////********** UNDO / REDO ***********/

function fileChange() {
  undoIndex = undoIndex+1;
  undoList[undoIndex] = currentFile;
  console.log("undo index is: " + undoIndex +" undoList is "+ undoList);
  wavesurfer.load(currentFile);
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





//dat.gui button stuff
//
var myEffect = function() {
  this.speed = 0.8;
  this.changePitch = false;
  this.typeOfSpeed = 'pizza';

  // Define render logic ...
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
            wavesurfer.isLooping();
            console.log("loop?");
            //
        },

        'rev': function() {  //REVERSE
            //0. prepare the infos
            var uploadFormData = new FormData();
            //console.log(droppedFiles);
            uploadFormData.append("tempFile", currentFile);
            uploadFormData.append("revers", true);

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
