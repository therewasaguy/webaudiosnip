var recordStartTime;

//<!--from https://github.com/muaz-khan/WebRTC-Experiment/blob/master/RecordRTC/RecordRTC-to-PHP/index.html -->
function PostBlob(blob, fileType, fileName) {
                    // FormData
                    var formData = new FormData();
                    formData.append(fileType + '-filename', fileName);
                    formData.append(fileType + '-blob', blob);

                    // progress-bar
//                    var hr = document.createElement('hr');
//                    container.appendChild(hr);
                    var strong = document.createElement('strong');
                    strong.innerHTML = fileType + ' upload progress: ';
                    container.appendChild(strong);
                    var progress = document.createElement('progress');
                    container.appendChild(progress);

                    // POST the Blob
                    xhr_record('save.php', formData, progress, function(fileURL) {
                            //container.appendChild(document.createElement('hr'));
                            var mediaElement = document.createElement(fileType);
                            
                            var source = document.createElement('source');
                            source.src = /*location.href +*/ fileURL;
                            console.log(location.href + fileURL);
                            
                            if(fileType == 'video') source.type = 'video/webm; codecs="vp8, vorbis"';
                            if(fileType == 'audio') source.type = 'audio/wav';
                            
                            mediaElement.appendChild(source);
                            currentFile = $.trim(fileURL);
                            fileChange();
                            
                            mediaElement.controls = true;
                            //container.appendChild(mediaElement);
                            //mediaElement.play();

                            progress.parentNode.removeChild(progress);
                            strong.parentNode.removeChild(strong);
                           // hr.parentNode.removeChild(hr);
                    });
            }

            var record = document.getElementById('record');
            var stop = document.getElementById('stop');
//            var deleteFiles = document.getElementById('delete');
            var recordAudio, recordVideo;
            var audio = document.querySelector('audio');

//            var preview = document.getElementById('preview');

            var container = document.getElementById('container');

            record.onclick = function() {

                    record.disabled = true;
                    var video_constraints = {
                            mandatory: { },
                            optional: []
                    };
                    navigator.getUserMedia({
                                    audio: true,
//                                    video: video_constraints
                            }, function(stream) {
                                    //preview.src = window.URL.createObjectURL(stream);
                                    //preview.play();

                                    // var legalBufferValues = [256, 512, 1024, 2048, 4096, 8192, 16384];
                                    // sample-rates in at least the range 22050 to 96000.
                                    recordAudio = RecordRTC(stream, {
                                            //bufferSize: 16384,
                                            //sampleRate: 45000
                                    });

                                    recordAudio.startRecording();
                					recStartTime = Date.now();


                                    stop.disabled = false;
                            });
            };

            var fileName;
            stop.onclick = function() {

                    record.disabled = false;
                    stop.disabled = true;

                    fileName = "MyRecording"+Math.round(Math.random() * 999) + 999;

                    recordAudio.stopRecording();
                    PostBlob(recordAudio.getBlob(), 'audio', fileName + '.wav');

                    //preview.src = '';
                    //deleteFiles.disabled = false;
            };

/*            deleteFiles.onclick = function() {
                    deleteAudioVideoFiles();
            };
*/
            function deleteAudioVideoFiles() {
                    deleteFiles.disabled = true;
                    if (!fileName) return;
                    var formData = new FormData();
                    formData.append('delete-file', fileName);
                    xhr_record('delete.php', formData, null, function(response) {
                            console.log(response);
                    });
                    fileName = null;
                    container.innerHTML = '';
            }

            function xhr_record(url, data, progress, callback) {
                    var request = new XMLHttpRequest();
                    request.onreadystatechange = function() {
                            if (request.readyState == 4 && request.status == 200) {
                                    callback(request.responseText);
                                    console.log(request.responseText);
                            }
                    };

                    request.onprogress = function(e) {
                            if(!progress) return;
                            if (e.lengthComputable) {
                                    progress.value = (e.loaded / e.total) * 100;
                                    progress.textContent = progress.value; // Fallback for unsupported browsers.
                            }

                            if(progress.value == 100){
                                    progress.value = 0;
                            }
                    };
                    request.open('POST', url);
                    request.send(data);
            }

            window.onbeforeunload = function() {
                    if (!!fileName) {
                            deleteAudioVideoFiles();
                            return 'It seems that you\'ve not deleted audio/video files from the server.';
                    }
            };

function postBlob() {
            // FormData
        var formData = new FormData();
        formData.append(fileType + '-filename', fileName);
        formData.append(fileType + '-blob', blob);

}