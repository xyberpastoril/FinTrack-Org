@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Scanning for {{ $event->name }}</div>

                <div class="card-body">
                    <!-- input group with button -->
                    <div class="input-group mb-3">
                        <select id="choose-camera" class="form-control" id="devices">
                            <option id="choose-camera-label" selected>Choose...</option>

                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="start-camera" disabled=true>Start
                                Camera</button>
                            <button class="btn btn-outline-danger" type="button" id="stop-camera"
                                style="display:none">Stop
                                Camera</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <video id="preview" style="width:100%;height:auto;border:1px solid #333;border-radius:5%"></video>
                        </div>
                        <div class="col-12 col-lg-9">
                            <div class="card">
                                <div class="card-body">
                                    <h5><span id="latest_scan_name">You haven't scanned anything yet.</span></h5>
                                    <p id="latest_scan_contents" class="card-text" style="display:none">ID Number: <span id="latest_scan_id_number"></span><br>
                                        Degree Program: <span id="latest_scan_degree_program"></span><br>
                                        Year Level: <span id="latest_scan_year_level"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>Logged Students</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID Number</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Degree Program</th>
                                <th scope="col">Year Level</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="logged-students">
                            <!-- js will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/jquery-3.6.3/jquery.min.js"></script>
<script src="/assets/instascan-1.0.0/instascan.js"></script>
<script>
    // Check whether the user is using mobile or tablet (for camera )

    $("#toggleRecentScans").click(function () {
        $('#recentScans-container').toggle('fast');
    });

    var _recentScansDiv = document.getElementById("recentScans");
    var audio = new Audio("{{ asset('beep.wav') }}");
    var video_cameras = [];

    let opts = {
        // Whether to scan continuously for QR codes. If false, use scanner.scan() to manually scan.
        // If true, the scanner emits the "scan" event when a QR code is scanned. Default true.
        continuous: true,

        // The HTML element to use for the camera's video preview. Must be a <video> element.
        // When the camera is active, this element will have the "active" CSS class, otherwise,
        // it will have the "inactive" class. By default, an invisible element will be created to
        // host the video.
        video: document.getElementById('preview'),

        // Whether to horizontally mirror the video preview. This is helpful when trying to
        // scan a QR code with a user-facing camera. Default true.
        // Automatically preferred depending whether the user uses mobile/tablet or desktop webcam.
        mirror: true,

        // Whether to include the scanned image data as part of the scan result. See the "scan" event
        // for image format details. Default false.
        captureImage: false,

        // Only applies to continuous mode. Whether to actively scan when the tab is not active.
        // When false, this reduces CPU usage when the tab is not active. Default true.
        backgroundScan: true,

        // Only applies to continuous mode. The period, in milliseconds, before the same QR code
        // will be recognized in succession. Default 5000 (5 seconds).
        refractoryPeriod: 5000,

        // Only applies to continuous mode. The period, in rendered frames, between scans. A lower scan period
        // increases CPU usage but makes scan response faster. Default 1 (i.e. analyze every frame).
        scanPeriod: 1
    };

    document.addEventListener("DOMContentLoaded", event => {
        let scanner = new Instascan.Scanner(opts);
        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length > 0) {
                // populate the dropdown with index
                for (var i = 0; i < cameras.length; i++) {
                    $('#choose-camera').append($('<option>', {
                        value: i,
                        text: cameras[i].name
                    }));
                }
                $('#choose-camera-label').remove();

                video_cameras = cameras;
                scanner.camera = video_cameras[0];

                $('#start-camera').prop('disabled', false);
            } else {
                console.error("No cameras found.");
            }
        }).catch(e => console.error(e));

        scanner.addListener('scan', content => {
            // content of scanned qr code will go through here.
            // must call api and return feedback that the qr code is scanned
            console.log(content);

            $("#latest_scan_name").html("<span class='text-muted'>Processing Data</span>");
            // $("#latestSection").html("<span class-'text-muted'>Give us a second.</span>");

            const url = window.location.href;

            // Use a regular expression to match the number between the last two slashes
            const match = url.match(/\/(\d+)\/[^/]*$/);

            // Extract the number from the first capturing group of the match
            const eventId = match && match[1];

            console.log(eventId); // Output: "2"


            var jqxhr = $.post(`/ajax/events/${eventId}/logs/store`, {
                _token: "{{ csrf_token() }}",
                id_number: content
            });

            jqxhr.done(function (response) {
                console.log(response);
                // Latest Scan
                if (response.student) {
                    // $("#latestName").html("<span class='text-success'>" + response.user.lastName + ', ' + response.user.firstName + ' ' + (response.user.middleName ? response.user.middleName[0] + '.' : "") + "</span>");
                    $("#latest_scan_name").html(`<span class='text-success'>${response.student.last_name}, ${response.student.first_name}</span>`);
                    $("#latest_scan_id_number").text(response.student.id_number);
                    $("#latest_scan_degree_program").text(response.degree_program.abbr);
                    $("#latest_scan_year_level").text(response.student.year_level);

                    // show the other fields
                    $("#latest_scan_contents").show();

                    // audio.play();

                    window.navigator.vibrate(200); // vibrate for 200ms

                    // Add to logged-students
                    var student = response.student;
                    var log = response.log;
                    var degree_program = response.degree_program;
                    var studentRow = `
                        <tr>
                            <th scope="row">${response.log.id}</th>
                            <td>${student.id_number}</td>
                            <td>${student.last_name}</td>
                            <td>${student.first_name}</td>
                            <td>${degree_program.abbr}</td>
                            <td>${student.year_level}</td>
                            <td>${log.status}</td>
                            <td>
                                <a href="/events/1/logs/${log.id}/delete" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    `;

                    // Add to recent scans
                    $('#logged-students').prepend(studentRow);

                } else {
                    $("#latest_scan_name").html(
                        "<span class='text-danger'>Scan unsuccessful (not on records).</span>"
                        );
                    // Hide the other fields
                    $("#latest_scan_contents").hide("");
                }
            });

            jqxhr.fail(function (response) {
                console.log(response)
                $("#latest_scan_name").html(
                    "<span class='text-danger'>Scan unsuccessful (invalid code).</span>");

                // Hide the other fields
                $("#latest_scan_contents").hide("");
            });
        });

        $('#choose-camera').on('change', function () {
            scanner.camera = video_cameras[this.value];
            $('#preview-spinner').remove();
        });

        $('#start-camera').on('click', function () {
            if(scanner.camera != null) {
                scanner.start();
                $('#preview-spinner').remove();
                $('#start-camera').hide();
                $('#stop-camera').show();
            }
        });

        $('#stop-camera').on('click', function () {
            scanner.stop();
            $('#start-camera').show();
            $('#stop-camera').hide();
        });

    });

</script>
@endsection
