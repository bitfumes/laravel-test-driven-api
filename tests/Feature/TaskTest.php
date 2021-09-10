<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_all_tasks_of_a_todo_list()
    {
        $task = Task::factory()->create();
        $response = $this->getJson(route('task.index'))->assertOk()->json();
        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
    }
}
