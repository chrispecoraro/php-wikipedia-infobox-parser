<?php
namespace Chrispecoraro\WikipediaInfoboxParser;

use \Illuminate\Support\Facades\Facade;

class InfoboxFacade extends Facade {

    protected static function getFacadeAccessor() { return 'infoboxService'; }

}