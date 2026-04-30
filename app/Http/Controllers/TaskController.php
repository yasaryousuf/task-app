<?php

namespace App\Http\Controllers;

use App\Enums\Task\Status;
use App\Events\TaskNonCompliant;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskStatusChangeRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    protected $activityLog;

    public function __construct(ActivityLog $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskQuery = Task::with(['assigned_to_user']);
        if (request('status'))
            $taskQuery = $taskQuery->where('status', request('status'));

        if (request('assigned_to'))
            $taskQuery = $taskQuery->where('assigned_to', request('assigned_to'));

        if (request('due')) {
            if (request('due') == 'due_today') {
                $taskQuery = $taskQuery->whereDate('due_date', '=', Carbon::today());
            } else if (request('due') == 'overdue') {
                $taskQuery = $taskQuery->whereDate('due_date', '<', Carbon::today());
            }
        }
        return view('tasks.index', [
            'tasks' => $taskQuery->get(),
            'task_statuses' => Status::cases(),
            'assigned_users' => User::select('name', 'id')->get()
        ]);
    }

    /**
     * change status of a task.
     */
    public function statusChange(TaskStatusChangeRequest $request)
    {
        try {
            DB::beginTransaction();
            $task = Task::find($request->task_id);
            $task->status = $request->status;
            if ($request->corrective_action)
                $task->corrective_action = $request->corrective_action;
            $task->save();
            $this->activityLog->log("Task ID: {$request->task_id} status changed to {$request->status}");
            if ($task->status == Status::NON_COMPLIANT)
                event(new TaskNonCompliant($task));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false], 500);
        }
        return response()->json(['success' => true, 'task' => $task], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            ...$request->validated(),
            'status' => 'pending'
        ]);
        return redirect()->back()->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json(['success' => true, 'task' => $task->toArray()], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request)
    {
        try {
            DB::beginTransaction();
            $task = Task::find($request->id);
            $data = [
                ...$request->validated(),
                'status' => $task->status
            ];
            $task->update($data);
            $this->activityLog->log("Task ID: {$request->id} updated");
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong.');
        }
        return redirect()->back()->with('success', 'Task updated successfully.');
    }
}
