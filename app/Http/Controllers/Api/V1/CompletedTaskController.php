<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class CompletedTaskController extends Controller
{
    use ApiResponse;
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return $this->formatApiResponse(null, 'Task not found', 404);
        }

        $task->is_completed = !$task->is_completed;
        $task->save();

        return $this->formatApiResponse(TaskResource::make($task), 'Task updated successfully');
    }
}
