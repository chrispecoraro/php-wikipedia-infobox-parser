<?php
namespace Chrispecoraro\WikipediaInfoboxParser;

interface InfoboxInterface {
    public function getInfobox($pageName = null);
}