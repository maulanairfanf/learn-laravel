<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $isCompleted = $request->input('is_completed');

        $user = $request->user();

        $tasksQuery = Task::query()
        ->with('priority')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc');

        if (!empty($search)) {
            $tasksQuery->where('name', 'like', '%' . $search . '%');
        }

        if (!is_null($isCompleted)) {
            $tasksQuery->where('is_completed', $isCompleted);
        }

        $tasks = $tasksQuery->paginate($perPage);


        $paginationInfo = [
            'total' => $tasks->total(),
            'current_page' => $tasks->currentPage(),
            'last_page' => $tasks->lastPage(),
        ];

        return $this->formatApiResponse(
            TaskResource::collection($tasks),
            '',
            200,
            $paginationInfo
        );
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
        $user = $request->user();

        $validatedData = $request->validated();

        if (!isset($validatedData['priority_id'])) {
            $validatedData['priority_id'] = 3;
        }

        $validatedData['user_id'] = $user->id;

        $task = Task::create($validatedData);

        $task->load('priority');

        return $this->formatApiResponse(TaskResource::make($task));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::where('id', $id)->where('user_id', request()->user()->id)->first();

        if (!$task) {
            return $this->formatApiResponse(null, 'Task not found', 404);
        }

        $task->load('priority');

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
    public function update(UpdateTaskRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $task = Task::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$task) {
            return $this->formatApiResponse(null, 'Task not found', 404);
        }

        if (isset($validatedData['name'])) {
            $task->name = $validatedData['name'];
        }

        if (isset($validatedData['priority_id'])) {
            $task->priority_id = $validatedData['priority_id'];
        }

        $task->save();
        $task->load('priority');

        return $this->formatApiResponse(TaskResource::make($task), 'Task updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       // Cari task berdasarkan ID dan user_id pengguna yang terautentikasi
       $task = Task::where('id', $id)->where('user_id', Auth::id())->first();

       // Jika task tidak ditemukan
       if (!$task) {
           return $this->formatApiResponse(null, 'Task not found', 404);
       }

       // Hapus task
       $task->delete();

       // Mengembalikan respons bahwa task berhasil dihapus
       return $this->formatApiResponse(null, 'Task deleted successfully');
    }

    public function getSuggestedTasks(Request $request)
    {
        $user_id = $request->user()->id;

        // Query untuk mengambil task yang sering dibuat oleh pengguna
        $suggestedTasks = Task::select('name')
            ->selectRaw('COUNT(*) as count')
            ->where('user_id', $user_id)
            ->groupBy('name')
            ->orderByDesc('count')
            ->take(5) // Ambil 5 task teratas
            ->get();

        $formattedData = $suggestedTasks->map(function ($task) {
            return ['name' => $task->name];
        });


        return $this->formatApiResponse($formattedData);

    }
}
