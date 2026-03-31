<?php

namespace App\Http\Controllers;

use App\Enums\Priority;
use App\Enums\Status;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['sometimes', Rule::in(array_column(Status::cases(), 'value'))],
        ]);

        $query = Task::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('due_date', 'asc')
            ->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found matching your criteria.',
                'data'    => []
            ], 200);
        }

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        //
        $task = Task::create($request->validated());
        $task->refresh();
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Task $task)
    {
        //
        $nextStatus = $task->status->next();

        if (!$nextStatus) {
            return response()->json([
                'message' => 'Invalid transition: Task is already completed.',
                'current_status' => $task->status->value
            ], 422);
        }

        $task->update([
            'status' => $nextStatus
        ]);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
        if ($task->status !== Status::DONE) {
            return response()->json([
                'message' => 'Action Forbidden: Only tasks marked as done can be deleted.',
                'current_status' => $task->status->value,
            ], 403);
        }

        $task->delete();
        return response()->noContent();
    }

    public function report(Request $request)
    {
        $request->validate([
            'date' => 'sometimes|date_format:Y-m-d',
        ]);

        $date = $request->query('date', now()->format('Y-m-d'));

        $tasks = Task::whereDate('due_date', $date)
            ->selectRaw('priority, status, COUNT(*) as count')
            ->groupBy('priority', 'status')
            ->get();

        $summary = [];

        foreach (Priority::cases() as $priority) {
            foreach (Status::cases() as $status) {
                $summary[$priority->value][$status->value] = 0;
            }
        }

        foreach ($tasks as $task) {
            $summary[$task->priority->value][$task->status->value] = $task->count;
        }

        return response()->json([
            'date'    => $date,
            'summary' => $summary,
        ]);
    }
}
