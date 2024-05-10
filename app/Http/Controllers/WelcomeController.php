<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        return response()
            ->json([
                'profile' => Profile::first(),
                'projects' => Project::limit(3)->get(),
                'experiences' => Experience::limit(5)->get(),
                'articles' => Post::limit(3)->get(),
            ]);
    }
}
