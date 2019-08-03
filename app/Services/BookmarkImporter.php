<?php

namespace App\Services;

use App\models\Bookmark;
use App\Services\BookmarkAvailabilityHandler;
use App\Services\BookmarkMetatagsHandler;
use App\Services\BookmarkAdultHandler;
use App\Services\BookmarkUserPrivacyHandler;
use App\Services\BookmarkAdultTagHandler;
use App\Services\BookmarkDomainTagHandler;
use App\Traits\DomainExtractorTrait;
use App\Services\BookmarkCreator;
use App\Services\BookmarkUserAssociator;
use Illuminate\Database\QueryException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use App\User;

class BookmarkImporter
{
    use DomainExtractorTrait;

    /**
     * @var BookmarkUserAssociator 
     */
    protected $bookmarkUserAssociator;

    /**
     * @var BookmarkCreator 
     */
    protected $bookmarkCreator;

    /**
     * @var BookmarkMetatagsHandler 
     */
    protected $bookmarkMetatagsHandler;

    /**
     * @var BookmarkAdultHandler 
     */
    protected $bookmarkAdultHandler;

    /**
     * @var BookmarkAvailabilityHandler 
     */
    protected $bookmarkAvailabilityHandler;

    /**
     * @var BookmarkUserPrivacyHandler 
     */
    protected $bookmarkUserPrivacyHandler;

    /**
     * @var BookmarkAdultTagHandler 
     */
    protected $bookmarkAdultTagHandler;
    /**
     * @var BookmarkDomainTagHandler 
     */
    protected $bookmarkDomainTagHandler;

    /**
     * BookmarkImporter Constructor.
     * 
     * @param BookmarkUserPrivacyHandler $bookmarkUserPrivacyHandler
     * @param BookmarkMetatagsHandler $bookmarkMetatagsHandler
     * @param BookmarkUserAssociator $bookmarkUserAssociator
     * @param BookmarkCreator $bookmarkCreator
     * @param BookmarkAvailabilityHandler $bookmarkAvailabilityHandler
     * @param BookmarkAdultHandler $bookmarkAdultHandler
     * @param BookmarkAdultTagHandler $bookmarkAdultTagHandler
     * @param BookmarkDomainTagHandler $bookmarkDomainTagHandler
     */
    public function __construct(
        BookmarkUserPrivacyHandler $bookmarkUserPrivacyHandler,
        BookmarkMetatagsHandler $bookmarkMetatagsHandler,
        BookmarkUserAssociator $bookmarkUserAssociator,
        BookmarkCreator $bookmarkCreator,
        BookmarkAvailabilityHandler $bookmarkAvailabilityHandler,
        BookmarkAdultHandler $bookmarkAdultHandler,
        BookmarkAdultTagHandler $bookmarkAdultTagHandler,
        BookmarkDomainTagHandler $bookmarkDomainTagHandler
    ) {
        $this->bookmarkUserPrivacyHandler = $bookmarkUserPrivacyHandler;
        $this->bookmarkMetatagsHandler = $bookmarkMetatagsHandler;
        $this->bookmarkUserAssociator = $bookmarkUserAssociator;
        $this->bookmarkCreator = $bookmarkCreator;
        $this->bookmarkAvailabilityHandler = $bookmarkAvailabilityHandler;
        $this->bookmarkAdultHandler = $bookmarkAdultHandler;
        $this->bookmarkAdultTagHandler = $bookmarkAdultTagHandler;
        $this->bookmarkDomainTagHandler = $bookmarkDomainTagHandler;
    }

    public function importFromTextFile($name)
    {
        $user = User::find(1);
        $urls = array_filter(file(storage_path("/app/{$name}"), FILE_IGNORE_NEW_LINES));

        foreach ($urls as $url) {
            $bookmarks[] =  $this->import($url, $user);
        }

        return $bookmarks;
    }

    public function multiImport($urls, User $user)
    {
        foreach ($urls as $url) {
            $bookmarks[] =  $this->import($url, $user);
        }

        return $bookmarks;
    }

    public function import($url, User $user)
    {

        try {
            $isNew = false;
            // check if it exists
            $bookmark = Bookmark::where("url", $url)->first();
            if (!$bookmark) {
                $isNew = true;
                $bookmark = $this->bookmarkCreator->create($url);
                $bookmark = $this->bookmarkAvailabilityHandler->handle($bookmark);
                $bookmark = $this->bookmarkMetatagsHandler->handle($bookmark);
                $bookmark = $this->bookmarkAdultHandler->handle($bookmark); // TODO make sure its available first ?
            }

            if ($user->bookmarks()->where('bookmarks.id', $bookmark->id)->first()) {
                return false;
            }

            $bookmark = $this->bookmarkUserAssociator->associate($bookmark, $user);
            $bookmark = $this->bookmarkUserPrivacyHandler->handle($bookmark, $user);

            // handle tags
            $bookmark = $this->bookmarkAdultTagHandler->handle($bookmark, $user);
            $bookmark = $this->bookmarkDomainTagHandler->handle($bookmark, $user);

            return $bookmark;
        } catch (QueryException | ProcessFailedException | ProcessTimedOutException $e) {
            //remove bookmark in case of error to perserve data consistency
            if ($isNew) {
                $bookmark->delete();
            }
            //log it
            return $e->getMessage();
        } catch (Exception $e) {
            //remove bookmark in case of error to perserve data consistency
            if ($isNew) {
                $bookmark->delete();
            }
            //log it
            return $e->getMessage();
        }
    }
}
