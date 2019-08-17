<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Tags;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Auth;

class TagsController extends Controller
{
    /**
     * @var Tags
     */
    protected $tagsService;

    /**
     * Create a new controller instance.
     *
     * @param Tags $tagsService
     * @return void
     */
    public function __construct(Tags $tagsService)
    {
        $this->tagsService = $tagsService;
    }

    /**
     * Get tags belonging to a user.
     *
     * @return JsonResponse
     */
    public function getUserTags(Request $request)
    {
        $tags = $this->tagsService->getUserTags(Auth::user(), $request->isPrivate);
        return response()->json(['tags' => $tags]);
    }
}
