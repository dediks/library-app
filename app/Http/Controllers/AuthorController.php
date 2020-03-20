<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function store()
    {
        Author::create(
            request()->only([
                'name', 'dob'
            ])
        );
    }
}
