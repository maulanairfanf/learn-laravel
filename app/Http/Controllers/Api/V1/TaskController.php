<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $tasksQuery = Task::query()->with('priority');

        if (!empty($search)) {
            $tasksQuery->where('name', 'like', '%' . $search . '%');
        }

        $tasks = $tasksQuery->paginate($perPage);


        $paginationInfo = [
            'total' => $tasks->total(),
            'current_page' => $tasks->currentPage(),
            'last_page' => $tasks->lastPage(),
        ];

        return $this->formatApiResponse([
            'tasks' => TaskResource::collection($tasks),
            'pagination' => $paginationInfo,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());
        $task->load('priority');
        return $this->formatApiResponse(TaskResource::make($task));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::with('priority')->find($id);

        if (!$task) {
            return $this->formatApiResponse(null, 'Task not found', 404);
        }

        return $this->formatApiResponse(TaskResource::make($task));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {

        $task->update($request->validated());
        return $this->formatApiResponse(TaskResource::make($task), 'Task updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

       // Mengembalikan respons untuk sukses menghapus
        return response()->json([
            'message' => 'Task deleted successfully'
        ], 204);
    }
}
