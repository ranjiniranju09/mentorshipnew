<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mentorship</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">


    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"  ></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"  />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css"> -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>


    @yield('styles')

    <style>
        body {
            font-family: "Ubuntu", sans-serif;
            background-color: #f4f7f6;
        }
        .sidebar {
            position: fixed;
            width: 250px;
            height: 100%;
            background: black;
            transition: 0.5s;
            overflow: hidden;
            padding-left: 5px;
        }
        .sidebar a {
            position: relative;
            display: block;
            width: 100%;
            text-decoration: none;
            color: white;
            padding: 15px 10px;
            /* margin-left: 15px; */
            margin-bottom: 10px;
            /* align-content: center; */
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar a:hover {
            color: white;
            background: blue;
        }
        
        .sidebar a i {
            margin-right: 15px;
        }
        
        .content {
            margin-left: 115px;
            width: 100%;
            /* width: max-content; */
            padding: 20px;
            transition: margin-left 0.5s;
        }
        .topbar, .dashboard-header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar {
            width: 100%;
            height: 60px;
            padding: 0 10px;
        }
        .toggle {
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            cursor: pointer;
            display: none; /* Hide by default */
        }
       
        .user {
            width: 50px;
            height: 50px;
            cursor: pointer;
        }
        .user img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .greeting {
            font-size: 24px;
            color: green;
            font-weight: bold;
        }
        .dashboard-header-wrapper {
            margin-bottom: 30px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                height: 100%;
                position: fixed;
                left: -250px;
                z-index: 1000;
            }
            .sidebar.active {
                left: 0;
            }
            .content {
                margin-left: 0;
            }
            .toggle {
                display: block;
            }
        }
        .academic-record, .assigned-tasks, .calendar, .mentor-details, .notifications, .recent-activities, .meetings {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        .academic-record:hover, .assigned-tasks:hover, .mentor-details:hover, .notifications:hover, .recent-activities:hover, .meetings:hover {
            transform: translateY(-5px);
        }
        .progress {
            height: 20px;
            border-radius: 10px;
        }
        .module-name {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }
        .calendar {
            max-width: 100%; /* Adjust to fit container */
            margin: 20px auto; /* Center and add space around */
            padding: 20px; /* Add padding inside */
            background-color: #fff; /* Background color */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow for 3D effect */
            border: 1px solid #ddd; /* Border around calendar */
        }

        /* Calendar Title Styling */
        .calendar-title {
            font-size: 24px; /* Title size */
            font-weight: 600; /* Title weight */
            color: #333; /* Title color */
            margin-bottom: 20px; /* Space below title */
            text-align: center; /* Center align title */
        }

        /* Calendar Item Styling */
        .calendar-item {
            padding: 20px;
            border-bottom: 2px solid #eee;
            transition: all 0.3s ease-in-out;
            background-color: #fafafa; /* Light background for items */
            border-radius: 4px; /* Rounded corners for items */
        }

        .calendar-item:last-child {
            border-bottom: none;
        }

        .calendar-item:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Calendar Time Styling */
        .calendar-time {
            font-size: 15px;
            color: #333;
            display: block;
            font-weight: bold;
        }

        /* Calendar Event Styling */
        .calendar-event {
            font-size: 16px;
            color: #666;
            display: block;
            margin-top: 5px; /* Space above event text */
        }

        /* FullCalendar Specific Styling */
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .fc-daygrid-day-number {
            color: #333;
        }

        .fc-daygrid-day-top {
            background-color: #ececec;
            border-radius: 4px;
            padding: 5px;
        }
        
        .notification-item, .mentor-detailsitems, .activity-item {
            padding: 20px 0;
            border-bottom: 2px solid #eee;
            transition: all 0.3s ease-in-out;
        }
        .notification-item:last-child, .activity-item:last-child {
            border-bottom: none;
        }
        .notification-item:hover, .activity-item:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .notification-time, .activity-time {
            font-size: 15px;
            color: #333;
            display: block;
            font-weight: bold;
        }
        .notification-event, .activity-event {
            font-size: 16px;
            color: #666;
            display: block;
        }
       
        .icon {
            font-size: 40px;
            margin-bottom: 20px;
            transition: transform 0.4s;
        }
        .custom-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        .custom-card:hover {
            transform: translateY(-5px);
        }
        .custom-card.modules {
            border-left: 5px solid #007bff;
            width:auto;
            height: 80%;
        }
        .custom-card.task {
            border-left: 5px solid #28a745;
            width:auto;
            height: 80%;
        }
        .custom-card.resources {
            border-left: 5px solid #ffc107;
            width:auto;
            height: 80%;
        }
        .custom-card.jobs {
            border-left: 5px solid #d24dff;
            width:auto;
            height: 80%;
        }
        
        .custom-card.modules .card-icon {
            color: #007bff;
        }
        .custom-card.task .card-icon {
            color: #28a745;
        }
        .custom-card.resources .card-icon {
            color: #ffc107;
        }
        .custom-card.jobs .card-icon {
            color: #d24dff;
        }
        /* FullCalendar overrides */
        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    @if(session('message'))
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                </div>
            </div>
        </div>
    @endif
    @if($errors->count() > 0)
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <ul class="list-unstyled mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <nav class="sidebar">

        @guest
        @else
        
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="icon">
                    <i class="fa-solid fa-circle-user fa-lg"></i> &nbsp;
                    </span> 
                    <span class="title">{{ Auth::user()->name }} </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('mentee.dashboard')}}" class="nav-link"><i class="fa fa-home navicon" aria-hidden="true"></i>&nbsp; Home</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="fa-solid fa-user"></i>&nbsp; Profile</a>
            </li>
            <li class="nav-item">
                <a href="{{route('menteemodule.index')}}" class="nav-link"><i class="fa-solid fa-book"></i>&nbsp; Modules</a>
            </li>
            <li class="nav-item">
                <a href="{{route('menteetasks.index')}}" class="nav-link"><i class="fa-solid fa-list-check" class="nav-link"></i>&nbsp; Task</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="fa-solid fa-certificate"></i> &nbsp; Certificate</a>
            </li>
            <li class="nav-item">
                <a href="{{route('calendar')}}" class="nav-link"><i class="fa-solid fa-calendar-days"></i>&nbsp; Calendar</a>
            </li>
            <li class="nav-item">
                <a href="{{route('mentee.tickets')}}" class="nav-link"><i class="fa-solid fa-headset"></i>&nbsp; Support</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa-solid fa-right-from-bracket fa-flip-horizontal"></i>
                                        {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                </form>
            </li>
            @endguest
        </ul>
        </div>
    </nav>
    <div class="content">
        @yield('content')
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

<!-- Add this to the head or before the closing </body> tag in your layout file -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')
</html>
