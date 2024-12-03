@extends('layouts.admin')
@section('content')

<style>
        @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");

        * {
            /* font-family: "Ubuntu", sans-serif; */
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .academic-record
        {
            align-items: center;
            margin-left: 400px;


        }
        .chartContainer 
        {
            margin-top: 15px;
            width: 500px;
        }
        .tableContainer
        {
            margin-top: 30px;
            width: 900px;
            margin-left: 200px;
        }

        .panel-default {
            margin-top: 30px;
            width: 900px;
            margin-left: 200px;
            text-align: center;
        }
       
    </style>

<div class="card">
    <div class="card-header">
        Module Progress
    </div>

    <div class="card-body">
    <div class="academic-record">
            <h4>Overall Module Progress</h4>
            <div class="toggle-buttons">
                <button class="btn btn-secondary" id="overallmoduleBtn">Overall Module Progress</button>
                <button class="btn btn-success" id="tableViewBtn">Table View</button> <!-- New Button -->
                <button class="btn btn-primary" id="moduleViewBtn">Module OverView</button> <!-- New Button -->
            </div>
            <div class="chartContainer" id="chartContainer">
                <canvas id="progressChart" class="chart-size"></canvas>
            </div>
        </div>

        <div class="tableContainer" id="tableContainer" style="display: none;">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Assigned Mentor</th>
                        <th>Module Completed</th>
                        <th>Modules Pending</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>Jane Smith</td>
                        <td>3</td>
                        <td>2</td>
                        <td><a href="#" class="btn btn-primary">View</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Mary Johnson</td>
                        <td>Chris Lee</td>
                        <td>4</td>
                        <td>1</td>
                        <td><a href="#" class="btn btn-primary">View</a></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>James Brown</td>
                        <td>Patricia Green</td>
                        <td>2</td>
                        <td>3</td>
                        <td><a href="#" class="btn btn-primary">View</a></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>

        <!-- Modal HTML code -->
        <div class="modal fade" id="studentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="studentDetailsModalLabel">Student Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Dynamic content goes here -->
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Name:</div>
                                <div class="col-sm-8" id="studentName"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Assigned Mentor:</div>
                                <div class="col-sm-8" id="assignedMentor"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Pending Tasks:</div>
                                <div class="col-sm-8" id="pendingTasks"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Completed Tasks:</div>
                                <div class="col-sm-8" id="completedTasks"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Sessions Attended:</div>
                                <div class="col-sm-8" id="sessionsAttended"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Total Modules:</div>
                                <div class="col-sm-8" id="totalModules"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Completed Modules:</div>
                                <div class="col-sm-8" id="completedModules"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Total Chapters:</div>
                                <div class="col-sm-8" id="totalChapters"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Completed Chapters:</div>
                                <div class="col-sm-8" id="completedChapters"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Total Quizzes:</div>
                                <div class="col-sm-8" id="totalQuizzes"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Completed Quizzes:</div>
                                <div class="col-sm-8" id="completedQuizzes"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Pending Quizzes:</div>
                                <div class="col-sm-8" id="pendingQuizzes"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default" id="moduleProgressContainer" style="display: none;">
            <div class="panel-heading">
                <h4>Modulewise Progress</h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>Module</th>
                            <th>Total Mentee Completed</th>
                            <th>Total Mentee Pending</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-toggle="collapse" data-target=".demo1-1" class="accordion-toggle">
                            <td><button class="btn btn-default btn-xs" onclick="toggleCollapse('.demo1-1')"><span><i class="fa-solid fa-eye"></i></span></button></td>
                            <td>Module 1</td>
                            <td>5</td>
                            <td>0</td>
                            <td>86%</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="hiddenRow">
                                <div class="accordian-body collapse demo1-1">
                                    <table class="table table-striped">
                                        <thead class="thead-light">
                                            <tr class="info">
                                                <th>Chapter</th>
                                                <th>Status</th>
                                                <th>Score</th>
                                                <th>Total Mentee Completed</th>
                                                <th>Total Mentee Pending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>chapter 1</td>
                                                <td>Completed</td>
                                                <td>80%</td>
                                                <td>10</td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 2</td>
                                                <td>Completed</td>
                                                <td>75%</td>
                                                <td>9</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 3</td>
                                                <td>Completed</td>
                                                <td>85%</td>
                                                <td>8</td>
                                                <td>2</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 4</td>
                                                <td>Completed</td>
                                                <td>80%</td>
                                                <td>9</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 5</td>
                                                <td>Completed</td>
                                                <td>90%</td>
                                                <td>7</td>
                                                <td>3</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr data-toggle="collapse" data-target=".demo1-2" class="accordion-toggle">
                            <td><button class="btn btn-default btn-xs" onclick="toggleCollapse('.demo1-2')"><span><i class="fa-solid fa-eye"></i></span></button></td>
                            <td>Module 2</td>
                            <td>4</td>
                            <td>1</td>
                            <td>80%</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="hiddenRow">
                                <div class="accordian-body collapse demo1-2">
                                    <table class="table table-striped">
                                        <thead class="thead-light">
                                            <tr class="info">
                                                <th>Chapter</th>
                                                <th>Status</th>
                                                <th>Score</th>
                                                <th>Total Mentee Completed</th>
                                                <th>Total Mentee Pending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>chapter 1</td>
                                                <td>Completed</td>
                                                <td>80%</td>
                                                <td>8</td>
                                                <td>2</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 2</td>
                                                <td>Completed</td>
                                                <td>85%</td>
                                                <td>7</td>
                                                <td>3</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 3</td>
                                                <td>Completed</td>
                                                <td>75%</td>
                                                <td>9</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 4</td>
                                                <td>Completed</td>
                                                <td>90%</td>
                                                <td>6</td>
                                                <td>4</td>
                                            </tr>
                                            <tr>
                                                <td>chapter 5</td>
                                                <td>Pending</td>
                                                <td>0%</td>
                                                <td>0</td>
                                                <td>10</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      
        <script>
            document.getElementById('overallmoduleBtn').addEventListener('click', function() {
            document.getElementById('chartContainer').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('moduleProgressContainer').style.display = 'none';
        });

        document.getElementById('tableViewBtn').addEventListener('click', function() {
            document.getElementById('chartContainer').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
            document.getElementById('moduleProgressContainer').style.display = 'none';
        });

        document.getElementById('moduleViewBtn').addEventListener('click', function() {
            document.getElementById('chartContainer').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('moduleProgressContainer').style.display = 'block';
        });

            // Data for the chart
            const totalModules = 10; // Replace with the total number of modules
            const completedModules = 7; // Replace with the number of completed modules
            const remainingModules = totalModules - completedModules;

            const moduleData = {
                labels: ['Students Completed All Modules', 'Students Pending Modules'],
                datasets: [{
                    label: 'Modules Count',
                    data: [completedModules, remainingModules],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Configuration options
            const config = {
                type: 'doughnut',
                data: moduleData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Overall Module Count'
                        }
                    }
                }
            };

            // Render the chart
            const progressChart = new Chart(
                document.getElementById('progressChart'),
                config
            );
        
        
            // Dummy data for students - this would come from your backend in a real application
            const students = {
                1: {
                    name: "John Doe",
                    mentor: "Jane Smith",
                    pendingTasks: 2,
                    completedTasks: 3,
                    sessionsAttended: 5,
                    totalModules: 10,
                    completedModules: 3,
                    totalChapters: 20,
                    completedChapters: 12,
                    totalQuizzes: 15,
                    completedQuizzes: 10,
                    pendingQuizzes: 5
                },
                2: {
                    name: "Mary Johnson",
                    mentor: "Chris Lee",
                    pendingTasks: 1,
                    completedTasks: 4,
                    sessionsAttended: 6,
                    totalModules: 10,
                    completedModules: 4,
                    totalChapters: 20,
                    completedChapters: 16,
                    totalQuizzes: 15,
                    completedQuizzes: 14,
                    pendingQuizzes: 1
                },
                3: {
                    name: "James Brown",
                    mentor: "Patricia Green",
                    pendingTasks: 3,
                    completedTasks: 2,
                    sessionsAttended: 4,
                    totalModules: 10,
                    completedModules: 2,
                    totalChapters: 20,
                    completedChapters: 8,
                    totalQuizzes: 15,
                    completedQuizzes: 6,
                    pendingQuizzes: 9
                }
            };

            // Function to populate modal with student details
            function populateModal(studentId) {
                const student = students[studentId];

                if (student) {
                    document.getElementById('studentName').textContent = student.name;
                    document.getElementById('assignedMentor').textContent = student.mentor;
                    document.getElementById('pendingTasks').textContent = student.pendingTasks;
                    document.getElementById('completedTasks').textContent = student.completedTasks;
                    document.getElementById('sessionsAttended').textContent = student.sessionsAttended;
                    document.getElementById('totalModules').textContent = student.totalModules;
                    document.getElementById('completedModules').textContent = student.completedModules;
                    document.getElementById('totalChapters').textContent = student.totalChapters;
                    document.getElementById('completedChapters').textContent = student.completedChapters;
                    document.getElementById('totalQuizzes').textContent = student.totalQuizzes;
                    document.getElementById('completedQuizzes').textContent = student.completedQuizzes;
                    document.getElementById('pendingQuizzes').textContent = student.pendingQuizzes;

                    $('#studentDetailsModal').modal('show');
                }
            }

            // Event listener for view buttons
            document.querySelectorAll('.btn-primary').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const studentId = event.target.closest('tr').children[0].textContent;
                    populateModal(studentId);
                });
            });
        </script>
        <script>
            function toggleCollapse(selector) {
                $(selector).collapse('toggle');
            }
        </script>