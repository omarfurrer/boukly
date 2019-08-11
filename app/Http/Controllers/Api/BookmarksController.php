<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookmarkImporter;
use App\Services\Bookmarks;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Auth;

class BookmarksController extends Controller
{
    /**
     * @var BookmarkImporter
     */
    protected $bookmarkImporter;

    /**
     * @var Bookmarks
     */
    protected $bookmarksService;

    /**
     * Create a new controller instance.
     *
     * @param BookmarkImporter $bookmarkImporter
     * @param Bookmarks $bookmarksService
     * @return void
     */
    public function __construct(BookmarkImporter $bookmarkImporter, Bookmarks $bookmarksService)
    {
        $this->bookmarkImporter = $bookmarkImporter;
        $this->bookmarksService = $bookmarksService;
    }

    /**
     * Store a bookmark for a user using the url.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $url = $request->url;

        $bookmark = $this->bookmarkImporter->import($url, $request->user());

        return response()->json(['bookmark' => $bookmark]);
    }

    /**
     * Check if user has bookmark.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function exists(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $url = $request->url;
        $exists = $this->bookmarksService->checkUserHasBookmark($url, $request->user());

        return response()->json(['exists' => $exists]);
    }

    /**
     * Get a user's bookmarks.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        $bookmarks = $this->bookmarksService->getUserBookmarks(Auth::user(), 100, $request->page ? $request->page : 1, $request->tags);
        return response()->json(['bookmarks' => $bookmarks]);
    }
}
