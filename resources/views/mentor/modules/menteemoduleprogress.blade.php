@extends('layouts.mentor')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Mentorship Meetings</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <th scope="col">Module Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Meeting Link</th>
                            <th>Recording</th>
                            <th>Action</th>
                            <th>Module Status</th> <!-- Module Status column -->
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
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
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                        @foreach($sessions[$module->id] as $session)
                                            <a href="{{ $session->sessionlink }}" target="_blank" class="btn btn-xs btn-primary">Meeting Link</a><br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                </td>
                                <td>
                                    @if(in_array($module->id, $moduleCompletionStatus)) 
                                        <!-- If the module is completed, show a green badge with a success label -->
                                        <!-- <span class="badge badge-success">Completed</span>  -->
                                    @else
                                        <!-- If the module is not completed, show the "Mark Module Completed" button -->
                                        <a href="{{ route('mentor.markChapterCompletion', $module->id) }}" class="btn btn-xs btn-primary">
                                            Mark Module Completed
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if(in_array($module->id, $moduleCompletionStatus)) 
                                        <!-- Show completed status in the Module Status column -->
                                        <span class="badge badge-success">Completed</span> 
                                    @else
                                        <!-- If the module is not completed, show a "Not Completed" label -->
                                        <span class="badge badge-warning">Not Completed</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="academic-record">
            <h4>Mentee Module Progress</h4>
            <canvas id="progressChart" class="chart-size"></canvas>
        </div>
    </div>
</div>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");

    * {
        font-family: "Ubuntu", sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        position: relative;
        width: 100%;
    }

    .academic-record {
        display: flex;
        margin-top: 15px;
        margin-left: 15px;
        align-content: center;
    }

    .chart-size {
        width: 100% !important;
        height: 400px !important;
    }

    .academic-record {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .academic-record h4 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }
</style>

<script>
    // Menu toggle
    const menuToggle = document.querySelector('.toggle');
    const navigation = document.querySelector('.navigation');
    const main = document.querySelector('.main');

    menuToggle.addEventListener('click', () => {
        navigation.classList.toggle('active');
        main.classList.toggle('active');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('progressChart').getContext('2d');
        var progressData = @json($progressData);
        var moduleNames = progressData.map(data => data.module_name);
        var completionPercentages = progressData.map(data => data.completion_percentage);

        var progressChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: moduleNames,
                datasets: [{
                    data: completionPercentages,
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',  // bg-success
                        'rgba(23, 162, 184, 0.7)',  // bg-info
                        'rgba(255, 193, 7, 0.7)',   // bg-warning
                        'rgba(220, 53, 69, 0.7)',   // bg-danger
                        'rgba(102, 16, 242, 0.7)'   // bg-purple
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(102, 16, 242, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Modules'
                        },
                        ticks: {
                            font: {
                                size: 10,      // Reduce font size to fit labels
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Progress (%)'
                        }
                    }
                }
            }
        });
    });
</script>

@endsection

@push('scripts')

@endpush
