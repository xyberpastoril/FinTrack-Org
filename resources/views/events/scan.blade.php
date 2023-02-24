@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Scanning for {{ $event->name }} [
                        Status:
                        @if($event->status == 'closed')
                            <span id="event-status" class="badge bg-secondary">Closed</span>
                        @elseif($event->status == 'timein')
                            <span id="event-status" class="badge bg-primary">Time-In</span>
                        @elseif($event->status == 'timeout')
                            <span id="event-status" class="badge bg-success">Time-Out</span>
                        @endif
                    ]
                    [
                        Scanned:
                        <strong id="log-count">{{ $log_count }}</strong>
                        of
                        <strong id="student-count">{{ $student_count }}</strong>
                    ]
                </div>

                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-scanner-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-scanner" type="button" role="tab" aria-controls="nav-scanner"
                                aria-selected="true">Scanner</button>
                            <button class="nav-link" id="nav-manual-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-manual" type="button" role="tab" aria-controls="nav-manual"
                                aria-selected="false">Manual Input</button>
                            {{-- <button class="nav-link" id="nav-logged-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-logged" type="button" role="tab" aria-controls="nav-logged"
                                aria-selected="false">Logged Students</button> --}}
                        </div>
                    </nav>

                    <!-- create tab content -->
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-scanner" role="tabpanel"
                            aria-labelledby="nav-scanner-tab">
                            <!-- input group with button -->
                            <div class="input-group mb-3">
                                <select id="choose-camera" class="form-control" id="devices">
                                    <option id="choose-camera-label" selected>Choose...</option>

                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="start-camera"
                                        disabled=true>Start
                                        Camera</button>
                                    <button class="btn btn-outline-danger" type="button" id="stop-camera"
                                        style="display:none">Stop
                                        Camera</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-3">
                                    <video id="preview"
                                        style="width:100%;height:auto;border:1px solid #333;border-radius:5%"></video>
                                </div>
                                <div class="col-12 col-lg-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5><span id="latest_scan_name">You haven't scanned anything yet.</span></h5>
                                            <p id="latest_scan_contents" class="card-text" style="display:none">ID Number: <span
                                                    id="latest_scan_id_number"></span><br>
                                                Degree Program: <span id="latest_scan_degree_program"></span><br>
                                                Year Level: <span id="latest_scan_year_level"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-manual" role="tabpanel" aria-labelledby="nav-manual-tab">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search Query"
                                    aria-label="Search Query" aria-describedby="button-addon2" id="manual_search_query" autocomplete="off`">
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Degree Program</th>
                                        <th scope="col">Year Level</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="list-students">
                                    <!-- js will populate this -->
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-logged" role="tabpanel" aria-labelledby="nav-logged-tab">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">ID Number</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Degree Program</th>
                                        <th scope="col">Year Level</th>
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
                    $("#latest_scan_name").html(
                        `<span class='text-success'>${response.student.last_name}, ${response.student.first_name}</span>`
                        );
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

                // update the event-status when necessary
                if (response.event.status == 'closed') {
                    $('#event-status').html('Closed');
                    $('#event-status').removeClass('bg-success');
                    $('#event-status').removeClass('bg-primary');
                    $('#event-status').addClass('bg-secondary');
                }
                else if(response.event.status == 'timein') {
                    $('#event-status').html('Time-In');
                    $('#event-status').removeClass('bg-secondary');
                    $('#event-status').removeClass('bg-success');
                    $('#event-status').addClass('bg-primary');
                }
                else if(response.event.status == 'timeout') {
                    $('#event-status').html('Time-Out');
                    $('#event-status').removeClass('bg-secondary');
                    $('#event-status').removeClass('bg-primary');
                    $('#event-status').addClass('bg-success');
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
            if (scanner.camera != null) {
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

        //
        $("#nav-manual-tab").addEventListener('shown.bs.tab', function () {
            // force input
            $('#search_query').focus();
        });
    });

    $(document).ready(function () {
        var query = $('#manual_search_query').val();
        console.log(query);

        const url = window.location.href;

        // Use a regular expression to match the number between the last two slashes
        const match = url.match(/\/(\d+)\/[^/]*$/);

        // Extract the number from the first capturing group of the match
        const eventId = match && match[1];

        console.log(eventId); // Output: "2"

        // fetch students list
        request = $.get(`/ajax/events/${eventId}/students/search/${query}`);

        request.done(function (response) {
            console.log(response)

            // clear the list-students
            $('#list-students').html("");

            // populate
            for (var i = 0; i < response.students.length; i++) {
                var student = response.students[i];
                var studentRow = `
                    <tr>
                        <th scope="row">${student.id}</th>
                        <td>${student.last_name}</td>
                        <td>${student.first_name}</td>
                        <td>${student.abbr}</td>
                        <td>${student.year_level}</td>
                        <td>
                            ${student.log_id
                                ? `<button class="btn btn-sm btn-success disabled">Logged</button>`
                                : `<button class="btn btn-sm btn-primary log-student" data-student-id="${student.id}">Log</button>`}
                        </td>
                    </tr>
                `;

                $('#list-students').append(studentRow);
            }
        });

        setInterval(function (){
            request = $.get(`/ajax/events/${eventId}/students/count/`);
            request.done(function (response) {
                console.log("Refreshing student and log count")
                console.log(response)

                // update log_count and student_count
                $('#log-count').html(response.log_count)
                $('#student-count').html(response.student_count)
            });

            request.fail(function(response){
                console.log(response)
            })
        }, 30000);

    });

    /**
     * Manual Search
     */

    $('#manual_search_query').on('input', function () {
        var query = $('#manual_search_query').val();
        console.log(query);

        const url = window.location.href;

        // Use a regular expression to match the number between the last two slashes
        const match = url.match(/\/(\d+)\/[^/]*$/);

        // Extract the number from the first capturing group of the match
        const eventId = match && match[1];

        console.log(eventId); // Output: "2"

        // fetch students list
        request = $.get(`/ajax/events/${eventId}/students/search/${query}`);

        request.done(function (response) {
            console.log(response)

            // clear the list-students
            $('#list-students').html("");

            // populate
            for (var i = 0; i < response.students.length; i++) {
                var student = response.students[i];
                var studentRow = `
                    <tr>
                        <th scope="row">${student.id}</th>
                        <td>${student.last_name}</td>
                        <td>${student.first_name}</td>
                        <td>${student.abbr}</td>
                        <td>${student.year_level}</td>
                        <td>
                            ${student.log_id
                                ? `<button class="btn btn-sm btn-success disabled">Logged</button>`
                                : `<button class="btn btn-sm btn-primary log-student" data-student-id="${student.id}">Log</button>`}
                        </td>
                    </tr>
                `;

                $('#list-students').append(studentRow);
            }

            // update the event-status when necessary
            if (response.event.status == 'closed') {
                $('#event-status').html('Closed');
                $('#event-status').removeClass('bg-success');
                $('#event-status').removeClass('bg-primary');
                $('#event-status').addClass('bg-secondary');
            }
            else if(response.event.status == 'timein') {
                $('#event-status').html('Time-In');
                $('#event-status').removeClass('bg-secondary');
                $('#event-status').removeClass('bg-success');
                $('#event-status').addClass('bg-primary');
            }
            else if(response.event.status == 'timeout') {
                $('#event-status').html('Time-Out');
                $('#event-status').removeClass('bg-secondary');
                $('#event-status').removeClass('bg-primary');
                $('#event-status').addClass('bg-success');
            }

            // update log_count and student_count
            $('#log-count').html(response.log_count)
            $('#student-count').html(response.student_count)
        });

    });

    // Log Student
    $(document).on('click', '.log-student', function(event){

        let e = event;
        // get data-student-id
        var studentId = $(this).data('student-id');
        console.log(studentId);

        const url = window.location.href;

        // Use a regular expression to match the number between the last two slashes
        const match = url.match(/\/(\d+)\/[^/]*$/);

        // Extract the number from the first capturing group of the match
        const eventId = match && match[1];

        var jqxhr = $.post(`/ajax/events/${eventId}/logs/store/byStudentId`, {
            _token: "{{ csrf_token() }}",
            student_id: studentId
        });

        jqxhr.done(function(response){
            console.log(response);
            console.log(e.currentTarget);

            // mark as logged
            $(e.currentTarget).addClass('btn-success');
            $(e.currentTarget).removeClass('btn-primary');

            // disable button
            $(e.currentTarget).prop('disabled', true);

            // rename
            $(e.currentTarget).text('Logged');

            // update the event-status when necessary
            if (response.event.status == 'closed') {
                $('#event-status').html('Closed');
                $('#event-status').removeClass('bg-success');
                $('#event-status').removeClass('bg-primary');
                $('#event-status').addClass('bg-secondary');
            }
            else if(response.event.status == 'timein') {
                $('#event-status').html('Time-In');
                $('#event-status').removeClass('bg-secondary');
                $('#event-status').removeClass('bg-success');
                $('#event-status').addClass('bg-primary');
            }
            else if(response.event.status == 'timeout') {
                $('#event-status').html('Time-Out');
                $('#event-status').removeClass('bg-secondary');
                $('#event-status').removeClass('bg-primary');
                $('#event-status').addClass('bg-success');
            }

            // update log_count and student_count
            $('#log-count').html(response.log_count)
            $('#student-count').html(response.student_count)
        })

        jqxhr.fail(function(response){
            console.log(response);
        })

        $('#manual_search_query').val("");
        $('#manual_search_query').focus();
    })

</script>
@endsection
