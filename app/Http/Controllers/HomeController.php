<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Bookmark;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $bookmarks = Auth::user()->bookmarks()->orderBy('id', 'desc')->get();
        $bookmarks = Auth::user()->bookmarks()->where('is_adult', 1)->orderBy('id', 'desc')->get();
        // $bookmarks = Auth::user()->bookmarks()->where('is_adult', 0)->orderBy('id', 'desc')->get();

        return view('home', ["bookmarks" => $bookmarks]);
    }
}
