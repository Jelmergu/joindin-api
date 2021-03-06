<?php

namespace Joindin\Api\Controller;

use Exception;
use Joindin\Api\Factory\MapperFactory;
use Joindin\Api\Model\LanguageMapper;
use PDO;
use Joindin\Api\Request;
use Teapot\StatusCode\Http;

class LanguagesController extends BaseApiController
{
    private $mapperFactory;

    public function __construct(array $config = [], MapperFactory $mapperFactory = null)
    {
        parent::__construct($config);
        $this->mapperFactory = $mapperFactory ?? new MapperFactory();
    }

    public function getLanguage(Request $request, PDO $db)
    {
        $language_id = $this->getItemId($request);
        // verbosity - here for consistency as we don't have verbose language details to return at the moment
        $verbose = $this->getVerbosity($request);

        $mapper = $this->mapperFactory->getMapper(LanguageMapper::class, $db, $request);
        $list   = $mapper->getLanguageById($language_id, $verbose);

        if (count($list['languages']) == 0) {
            throw new Exception('Language not found', Http::NOT_FOUND);
        }

        return $list;
    }

    public function getAllLanguages(Request $request, PDO $db)
    {
        // verbosity - here for consistency as we don't have verbose language details to return at the moment
        $verbose = $this->getVerbosity($request);

        // pagination settings
        $start          = $this->getStart($request);
        $resultsperpage = $this->getResultsPerPage($request);

        $mapper = $this->mapperFactory->getMapper(LanguageMapper::class, $db, $request);

        return $mapper->getLanguageList($resultsperpage, $start, $verbose);
    }
}
