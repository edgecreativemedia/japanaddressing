<?php

namespace EdgeCreativeMedia\JapanAddressing\Tests\Repository;

use EdgeCreativeMedia\JapanAddressing\Repository\DefinitionTranslatorTrait;

/**
 * Dummy repository used for testing the DefinitionTranslatorTrait.
 */
class DummyRepository
{
    use DefinitionTranslatorTrait;

    public function runTranslateDefinition($definition, $locale = null)
    {
        return $this->translateDefinition($definition, $locale);
    }
}
