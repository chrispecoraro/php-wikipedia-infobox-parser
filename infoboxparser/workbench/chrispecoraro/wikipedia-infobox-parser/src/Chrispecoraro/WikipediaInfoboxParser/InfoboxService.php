<?php
namespace Chrispecoraro\WikipediaInfoboxParser;

use InfoxboxInterface;

class InfoboxService {
    protected $infoboxRepository;

    function __construct(InfoboxInterface $infoboxRepository)
    {
        $this->infoboxRepository = $infoboxRepository;
    }

    public function getInfobox($name=null){
        if (strlen($name)>0){
            return $this->infoboxRepository->getInfobox($name);
        }
    }

}