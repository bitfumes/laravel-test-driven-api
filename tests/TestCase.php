<?php

namespace Tests;

use App\Models\Label;
use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use App\Models\WebService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function createTodoList($args = [])
    {
        return TodoList::factory()->create($args);
    }

    public function createTask($args = [])
    {
        return Task::factory()->create($args);
    }

    public function createUser($args = [])
    {
        return User::factory()->create($args);
    }

    public function authUser()
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);
        return $user;
    }

    public function createLabel($args = [])
    {
        return Label::factory()->create($args);
    }

    public function createWebService($args = [])
    {
        return WebService::factory()->create($args);
    }
}
