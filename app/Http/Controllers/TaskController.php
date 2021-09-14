<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index(TodoList $todo_list)
    {
        $tasks = $todo_list->tasks;
        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request, TodoList $todo_list)
    {
        $task =  $todo_list->tasks()->create($request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(Task $task, Request $request)
    {
        $task->update($request->all());
        return new TaskResource($task);
    }
}
