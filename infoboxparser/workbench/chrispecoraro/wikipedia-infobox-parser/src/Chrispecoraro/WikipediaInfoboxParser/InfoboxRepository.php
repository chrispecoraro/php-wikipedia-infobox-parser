<?php
namespace Chrispecoraro\WikipediaInfoboxParser;

class InfoboxRepository implements InfoboxInterface {

    protected $description = 'Parses Wikipedia Infobox.';

    protected $infoboxModel;

    function __construct(Infobox $infobox)
    {
        $this->infoboxModel = $infobox;
    }

    public function getInfobox($name = null){
        $this->infoboxModel->getInfobox($name);

    }
}