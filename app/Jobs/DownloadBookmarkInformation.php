<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\models\Bookmark;
use App\User;
use App\Services\BookmarkAvailabilityHandler;
use App\Services\BookmarkAdultHandler;
use App\Services\BookmarkMetatagsHandler;
use App\Services\BookmarkUserPrivacyHandler;
use App\Services\BookmarkDomainTagHandler;
use App\Services\BookmarkAdultTagHandler;

class DownloadBookmarkInformation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Bookmark
     */
    protected $bookmark;

    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param Bookmark $bookmark
     * @param User $user
     * @return void
     */
    public function __construct(Bookmark $bookmark, User $user)
    {
        $this->bookmark = $bookmark;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        BookmarkAvailabilityHandler $bookmarkAvailabilityHandler,
        BookmarkMetatagsHandler $bookmarkMetatagsHandler,
        BookmarkAdultHandler $bookmarkAdultHandler,
        BookmarkUserPrivacyHandler $bookmarkUserPrivacyHandler,
        BookmarkAdultTagHandler $bookmarkAdultTagHandler,
        BookmarkDomainTagHandler $bookmarkDomainTagHandler
    ) {
        $bookmark = $bookmarkAvailabilityHandler->handle($this->bookmark);
        $bookmark = $bookmarkMetatagsHandler->handle($bookmark);
        $bookmark = $bookmarkAdultHandler->handle($bookmark); // TODO make sure its available first ?
        $bookmark = $bookmarkUserPrivacyHandler->handle($bookmark, $this->user);
        $bookmark = $bookmarkAdultTagHandler->handle($bookmark, $this->user);
        $bookmark = $bookmarkDomainTagHandler->handle($bookmark, $this->user);
    }
}
