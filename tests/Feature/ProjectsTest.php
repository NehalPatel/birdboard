<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function only_authenticated_users_can_create_projects()
    {
        // $this->withoutExceptionHandling();

        // $attributes = factory('App\Models\Project')->raw(['owner_id' => null]);
        // $this->post('/projects', $attributes)->assertSessionHasErrors('owner_id');

        $attributes = factory('App\Models\Project')->raw();
        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->actingAs(factory('App\User')->create());

        $this->withoutExceptionHandling();

        $attributes = [
            'title' =>  $this->faker->sentence(),
            'description' => $this->faker->paragraph()
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** #\@test */
    public function a_user_can_view_a_project()
    {
        $this->actingAs(factory('App\User')->create());

        $this->withoutExceptionHandling();

        $project = factory('App\Models\Project')->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Models\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Models\Project')->raw(['description' => '']);

        $this->post('/projects', [])->assertSessionHasErrors('description');
    }


}
