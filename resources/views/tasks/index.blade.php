<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Task app') }}</title>


    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @endif

</head>

<body>

    <main>
        <div class="container py-4">
            @if (session('success'))
            <div class="alert alert-success">
                <ul>
                    <li>{{ session('success') }}</li>
                </ul>
            </div>
            @endif
            <!-- Button trigger modal -->
            <div class="mb-2">
                <button id="create-task" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal" data-toggle="modal" data-target="#taskModal">
                    Create Task
                </button>

            </div>
            <form class="row gy-2 gx-3 align-items-center" action="" method="get">
                <div class="col-auto">
                    <label class="visually-hidden" for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Select task status ...</option>
                        @foreach($task_statuses as $task_status)
                        <option value="{{$task_status->value}}" {{ request()->get('status') == $task_status->value ? 'selected' : '' }}>{{$task_status->label()}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="visually-hidden" for="assigned_to">Assigned to</label>
                    <select class="form-select" id="assigned_to" name="assigned_to">
                        <option value="">Select assigned to user ...</option>
                        @foreach($assigned_users as $assigned_user)
                        <option value="{{$assigned_user->id}}" {{ request()->get('assigned_to') == $assigned_user->id ? 'selected' : '' }}>{{$assigned_user->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select class="form-select" id="due" name="due">
                        <option value="" {{ empty(request()->get('due')) ? 'selected' : '' }}>All</option>
                        <option value="due_today" {{ request()->get('due') == 'due_today' ? 'selected' : '' }}>Due today</option>
                        <option value="overdue" {{ request()->get('due') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due</th>
                        <th>Assigned to</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($tasks) > 0)
                    @foreach($tasks as $task)
                    <tr class="task-row {{$task->is_overdue ? 'table-warning' : ''}}">
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->priority->label() }}</td>
                        <td class="status">{{ $task->status->label() }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>{{ $task->assigned_to_user->name }}</td>
                        <td>
                            <button type="button" class="edit-task-btn btn btn-primary btn-sm m-1" data-id="{{$task->id}}" data-bs-toggle="modal" data-bs-target="#editTaskModal">Edit</button>
                            @if($task->status->value == 'pending')
                            <form name="task-status-change" action="{{url('/tasks/status/change')}}" method="POST">
                                @csrf()
                                <input type="hidden" name="task_id" value="{{$task->id}}" />
                                <button type="submit" class="change_status btn btn-success btn-sm m-1" data-status="completed">Mark as complete</button>
                                <button type="button" class="mark_as_non_compliant btn btn-warning btn-sm m-1">Mark as non compliant</button>
                                <div class="corrective_action_section d-none">
                                    <input type="text" name="corrective_action" class="m-1" />
                                    <button type="submit" class="change_status btn btn-warning btn-sm m-1" data-status="non_compliant">Save</button>
                                </div>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4">
                            No tasks available.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <!-- Create Modal -->
            <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{url('tasks/store')}}" method="POST">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="taskModalLabel">Create task</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="due_date" class="col-sm-2 col-form-label">Due date</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="due_date" class="col-sm-2 col-form-label">Priority</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="priority" required>
                                            <option selected>Select priority</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="due_date" class="col-sm-2 col-form-label">User</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="assigned_to" required>
                                            <option selected>Assign to</option>
                                            @foreach($assigned_users as $assigned_user)
                                            <option value="{{$assigned_user->id}}">{{$assigned_user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- <button type="submit" class="btn btn-primary">Save</button> -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Edit Modal -->
            <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <form action="{{url('tasks/edit')}}" method="POST">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editTaskModalLabel">Edit task</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" />
                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="due_date" class="col-sm-2 col-form-label">Due date</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="due_date" class="col-sm-2 col-form-label">Priority</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="priority" required>
                                            <option selected>Select priority</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="due_date" class="col-sm-2 col-form-label">User</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" name="assigned_to" required>
                                            <option selected>Assign to</option>
                                            @foreach($assigned_users as $assigned_user)
                                            <option value="{{$assigned_user->id}}">{{$assigned_user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- <button type="submit" class="btn btn-primary">Save</button> -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>