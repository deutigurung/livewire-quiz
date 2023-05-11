<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $query = Quiz::withCount('questions')->has('questions')
                ->when(auth()->guest() || !auth()->user()->is_admin,function($q){
                    return $q->where('published',true);
                })
                ->orderBy('id')->get();
        $public = $query->where('public',true);
        $registered = $query->where('public',false);

        return view('home',compact('public', 'registered'));
    }
}
