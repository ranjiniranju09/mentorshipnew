@extends('layouts.new_mentee')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="dashboard-header-wrapper">
                <div class="topbar">
                    <div class="dashboard-header">
                        <i class="fa-solid fa-graduation-cap fa-beat fa-2xl"></i>
                        <span class="greeting">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="toggle" id="toggle-btn">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                    <div class="search">
                        <label>
                            <input type="text" placeholder="Search here">
                                <ion-icon name="search-outline"></ion-icon>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="custom-card modules">
                        <a href="{{route('menteesessions.index')}}"><i class=" icon fa-solid fa-diagram-project fa-2xl"></i></a>
                                </br>
                        <h5>Modules</h5>
                        
                    </div>
                    
                </div>
                <div class="col-md-3">
                    <div class="custom-card task">
                               <a href="{{route('menteetasks.index')}}"> <i class="icon fa-solid fa-list-check fa-2xl"></i></a>
                               </br>
                                <h5>Task</h5>
                                
                    </div>
                    
                </div>
                <div class="col-md-3">
                    <div class="custom-card resources">
                            <a href="{{route('knowledgebank.index')}}"><i class=" icon fa-solid fa-link fa-2xl"></i></a>
                            </br>
                                <h5>Knowledge Bank</h5>
                                
                    </div>
                    
                </div>
                <div class="col-md-3">
                    <div class="custom-card jobs">
                            <a href="{{route('opportunities.index')}}"><i class="icon fa-solid fa-briefcase fa-2xl"></i></a>
                            </br>
                                <h5>Opportunities</h5>
                                
                    </div>
                    
                </div>
                
            </div>
            <div class="row">
                {{--<div class="col-md-12">
                    <div class="academic-record">
                        <h4>Academic Record</h4>
                        @foreach($progressData as $data)
                            <div class="module-name">{{ $data['module_name'] }}</div>
                            <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: {{ $data['completion_percentage'] }}%;" aria-valuenow="{{$data['completion_percentage'] }}" 
                            aria-valuemin="0" aria-valuemax="100">
                            {{ $data['completion_percentage'] }}%
                            </div>
                            </div>
                        @endforeach
                        
                        
                        
                    </div>
                </div>--}}
                
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4>Sessions</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Module Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                                @if(is_null($module->deleted_at)) <!-- Skip deleted modules -->
                                <tr>
                                    <td>{{ $module->name }}</td>
                                    <td>
                                        @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                            @foreach($sessions[$module->id] as $session)
                                                @if(!empty($session->sessiondatetime))
                                                    <span class="badge badge-primary">Scheduled</span>
                                                @else
                                                    <span class="badge badge-dark">Not Scheduled</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="badge badge-dark">Not Scheduled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                            @foreach($sessions[$module->id] as $session)
                                                {{ $session->sessiondatetime }}<br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                            @foreach($sessions[$module->id] as $session)
                                                <a href="{{ $session->sessionlink }}" target="_blank" class="btn btn-xs btn-primary">Join Session</a><br>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
        <div class="col-md-4">
            <div class="mentor-details" style="background-color:#ffc107;">
                    <h4>Assigned Mentor</h4>
                <div class="mentor-detailsitems">
                    <span class="notification-event"> Mentor name: {{$mentorName}}</span>
                </div>
                        
            </div>
            <div class="notifications">
                <h4>Notifications</h4>
                    <div class="notification-item">
                        <span class="notification-time">Time</span>
                        <span class="notification-event">Assignment due</span>
                    </div>
                    <div class="notification-item">
                        <!-- <span class="notification-time">13:00</span>
                        <span class="notification-event">New lecture available</span> -->
                    </div>
            </div>
            <div class="recent-activities">
                <h4>Recent Activities</h4>
                <div class="activity-item">
                    <span class="activity-time">Yesterday</span>
                    <span class="activity-event">Completed Assignment 1</span>
                </div>
                <div class="activity-item">
                    <!-- <span class="activity-time">2 days ago</span>
                    <span class="activity-event">Joined new course: Biology</span> -->
                </div>
            </div>
            <!-- <div class="calendar">
                <h4>Calendar</h4>
                <div id='calendar'></div>
            </div> -->
        </div>
    </div>
     
</div>

@endsection
@section('scripts')
<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.16/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- FullCalendar JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: true,
            events: @json($calendarSessions ?? [])
        });
    });
</script>

    <script>
        $(document).ready(function() {
            // Toggle sidebar on hamburger menu click
            $('#toggle-btn').click(function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
@endsection
