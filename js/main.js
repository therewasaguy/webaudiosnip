'use strict';

// Create an instance
var wavesurfer = Object.create(WaveSurfer);

//adding global variables
var currentFile;
var timeMs;
var markerR;
var markerG;

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
    //wavesurfer.load('example/media/beep2_angels.wav');
    //<?php echo(json_encode($myVariable)); ?>;

    // Start listening to drag'n'drop on document
    wavesurfer.bindDragNDrop('#drop');

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
    console.log("30000");

});


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
                 console.log("the current file is " + currentFile)
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

// Bind buttons and keypresses
(function () {
    var eventHandlers = {
        'play': function () {
            wavesurfer.playPause();
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
            console.log(wavesurfer.isLooping)
            console.log("loop?");
            //
        },

        'rev': function() {
            console.log("reverse");

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
                success : function(ret) {
                         // callback function
                         //console.log(ret);
                         currentFile = $.trim(ret);
                         console.log(currentFile);
                         wavesurfer.load(currentFile);
//                         wavesurfer.load('example/media/'+currentFile);
                         //wavesurfer load that file
                         //set the current file
                }
               });

            // 2. get the current file back from PHP and wavesurfer.load it


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
