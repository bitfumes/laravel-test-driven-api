<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_all_todo_list()
    {
        // preperation / prepare
        TodoList::factory()->create(['name' => 'my list']);

        // action / perform
        $response = $this->getJson(route('todo-list.store'));

        // assertion / predict
        $this->assertEquals(1,count($response->json()));
        $this->assertEquals('my list',$response->json()[0]['name']);
    }

    public function test_fetch_single_todo_list()
    {
        $list = TodoList::factory()->create();
        $response = $this->getJson(route('todo-list.show', $list->id))
                    ->assertOk()
                    ->json();

        $this->assertEquals($response['name'] ,$list->name);
    }
}
