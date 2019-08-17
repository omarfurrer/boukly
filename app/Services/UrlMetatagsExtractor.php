<?php

namespace App\Services;

use App\Services\NodeScriptRunners\UrlMetatagsExtractorNodeScriptRunner;
use Log;

class UrlMetatagsExtractor
{

    /**
     * All meta tags.
     * 
     * @var Object 
     */
    protected $metaTags;

    /**
     * Page title.
     * 
     * @var string 
     */
    protected $title;

    /**
     * Page description.
     *  
     * @var String 
     */
    protected $description;

    /**
     * Page thumbnail URL.
     * 
     * @var string 
     */
    protected $image;

    /**
     * @var UrlMetatagsExtractorNodeScriptRunner 
     */
    protected $urlMetatagsExtractorNodeScriptRunner;

    /**
     * Constructor.
     * 
     * @param UrlMetatagsExtractorNodeScriptRunner $urlMetatagsExtractorNodeScriptRunner
     */
    public function __construct(UrlMetatagsExtractorNodeScriptRunner $urlMetatagsExtractorNodeScriptRunner)
    {
        $this->urlMetatagsExtractorNodeScriptRunner = $urlMetatagsExtractorNodeScriptRunner;
    }

    /**
     * Reset class.
     */
    public function reset()
    {
        $this->setMetaTags(null);
        $this->setTitle(null);
        $this->setDescription(null);
        $this->setImage(null);
    }

    /**
     * Extract all meta tags from a URL.
     * 
     * @param string $url
     * @return Object
     */
    public function extract($url)
    {
        Log::debug("[UrlMetatagsExtractor][extract] Attempting to extract meta tags.", [
            'url' => $url
        ]);

        $this->reset();

        $scriptOutput = $this->urlMetatagsExtractorNodeScriptRunner->run([
            'url' => $url
        ]);

        Log::debug("[UrlMetatagsExtractor][extract] Meta tags extraction output.", [
            'url' => $url,
            'response' => $scriptOutput
        ]);

        // log error
        if ($scriptOutput->error) {
            Log::error("[UrlMetatagsExtractor][extract] Error extracting meta tags.", [
                'url' => $url,
                'details' => $scriptOutput->errorDetails
            ]);
            throw new Exception('Error extracting meta tags.');
        }

        $metaTags = $scriptOutput->metaTags;

        $this->setMetaTags($metaTags);

        // extract and set title
        $this->extractTitle($metaTags);

        // extract and set description
        $this->extractDescription($metaTags);

        // extract and set image
        $this->extractImage($metaTags);

        return $this->getMetaTags();
    }

    /**
     * Extract and set title.
     * 
     * @param Object $metaTags
     * @return string
     */
    public function extractTitle($metaTags)
    {
        $title = $this->generalTitleExists($metaTags) ? $metaTags->general->title : null;

        $this->setTitle($title);

        return $title;
    }

    /**
     * Extract and set description.
     * 
     * @param Object $metaTags
     * @return string
     */
    public function extractDescription($metaTags)
    {
        $description = $this->openGraphDescriptionExists($metaTags) ? (is_array($metaTags->openGraph->description) ? $metaTags->openGraph->description[0] : $metaTags->openGraph->description) : ($this->generalDescriptionExists($metaTags) ? $metaTags->general->description : null);

        $this->setDescription($description);

        return $description;
    }

    /**
     * Check if URL has open graph description.
     * 
     * @param Object $metaTags
     * @return boolean
     */
    public function openGraphDescriptionExists($metaTags)
    {
        if (empty($metaTags->openGraph->description)) {
            return false;
        }
        return true;
    }

    /**
     * Check if URL has a general description.
     * 
     * @param Object $metaTags
     * @return boolean
     */
    public function generalDescriptionExists($metaTags)
    {
        if (empty($metaTags->general->description)) {
            return false;
        }
        return true;
    }

    /**
     * Check if URL has a general title.
     * 
     * @param Object $metaTags
     * @return boolean
     */
    public function generalTitleExists($metaTags)
    {
        if (empty($metaTags->general->title)) {
            return false;
        }
        return true;
    }

    /**
     * Extract and set image.
     * 
     * @param Object $metaTags
     * @return string
     */
    public function extractImage($metaTags)
    {
        $image = $this->openGraphImageExists($metaTags) ? (is_array($metaTags->openGraph->image) ? $metaTags->openGraph->image[0]->url : $metaTags->openGraph->image->url) : null;

        $this->setImage($image);

        return $image;
    }

    /**
     * Check if URL has open graph image.
     * 
     * @param Object $metaTags
     * @return boolean
     */
    public function openGraphImageExists($metaTags)
    {
        // if (empty($metaTags->openGraph->image->url)) {
        if (empty($metaTags->openGraph->image)) {
            return false;
        }
        return true;
    }

    /**
     * Meta tags getter.
     * 
     * @return Object
     */
    public function getMetaTags()
    {
        return $this->metaTags;
    }

    /**
     * Title getter.
     * 
     * @return String
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Description getter.
     * 
     * @return String
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Image URL getter.
     * 
     * @return String
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Meta tags setter.
     * 
     * @param Object $metaTags
     */
    public function setMetaTags($metaTags)
    {
        $this->metaTags = $metaTags;
    }

    /**
     * Title setter.
     * 
     * @param String $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Description setter.
     * 
     * @param String $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Image URL setter.
     * 
     * @param String $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}
