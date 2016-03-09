<?php

namespace EdgeCreativeMedia\JapanAddressing\Repository;

use EdgeCreativeMedia\JapanAddressing\Model\Subdivision;

/**
 * Provides the subdivision list.
 *
 * Choosing the source at runtime allows integrations (such as the symfony
 * bundle) to stay agnostic about the intl library they need.
 */
class SubdivisionRepository implements SubdivisionRepositoryInterface
{
    use DefinitionTranslatorTrait;

    /**
     * The path where subdivision definitions are stored.
     *
     * @var string
     */
    protected $definitionPath;

    /**
     * Subdivision definitions.
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * Creates a SubdivisionRepository instance.
	 *
     * @param string $definitionPath Path to the subdivision definitions.
     *                               Defaults to 'resources/subdivision/'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ?: __DIR__ . '/../../resources/subdivision/';
        
    }

    /**
     * {@inheritdoc}
     */
    public function get($id, $subdivisionType, $locale = null)
    {	
    	/*
        $idParts = explode('-', $id);
        if (count($idParts) < 2) {
            // Invalid id, nothing to load.
            return null;
        }
		*/
        // The default ids are constructed to contain the country code
        // and parent id. For "BR-AL-64b095" BR is the country code and BR-AL
        // is the parent id.
        /*
        array_pop($idParts);
        $countryCode = $idParts[0];
        $parentId = implode('-', $idParts);
        if ($parentId == $countryCode) {
            $parentId = null;
        }
        */
        $definitions = $this->loadDefinitions($subdivisionType);

        return $this->createSubdivisionFromDefinitions($id, $definitions, $locale);
    }


    /**
     * {@inheritdoc}
     */
    public function getAll($subdivisionType, $locale = null)
    {
        $definitions = $this->loadDefinitions($subdivisionType);
        if (empty($definitions)) {
            return [];
        }

        $subdivisions = [];
        foreach (array_keys($definitions['subdivisions']) as $id) {
            $subdivisions[$id] = $this->createSubdivisionFromDefinitions($id, $definitions, $locale);
        }

        return $subdivisions;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($subdivisionType, $locale = null)
    {
        $definitions = $this->loadDefinitions($subdivisionType);
        if (empty($definitions)) {
            return [];
        }

        $list = [];
        foreach ($definitions['subdivision'] as $id => $definition) {
            $definition = $this->translateDefinition($definition, $locale);
            $list[$id] = $definition['name'];
        }

        return $list;
    }

    /**
     * Loads the subdivision definitions.
     *
     * @return array The subdivision definitions.
     */
    protected function loadDefinitions($subdivisionType)
    {
    	//load correct subdivision
    	switch ($subdivisionType) {    
			case 'region':
				$subdivisionFilename = 'japanregions.json';
			break;
			case 'prefecture':
				$subdivisionFilename = 'japanprefectures.json';
			break;   	
 			case 'city':
				$subdivisionFilename = 'japancities.json';
			break;
    	}
    	
    	$filename = $this->definitionPath . $subdivisionFilename;
        if ($rawDefinition = @file_get_contents($filename)) {
            $this->definitions = json_decode($rawDefinition, true);
        }

        return $this->definitions;
    }

    /**
     * Creates a subdivision object from the provided definitions.
     *
     * @param int    $id         The subdivision id.
     * @param array  $definition The subdivision definitions.
     * @param string $locale     The locale (e.g. fr-FR).
     *
     * @return Subdivision
     */
    protected function createSubdivisionFromDefinitions($id, array $definitions, $locale)
    {
        if (!isset($definitions['subdivision'][$id])) {
            // No matching definition found.
            return null;
        }

        $definition = $this->translateDefinition($definitions['subdivision'][$id], $locale);
        // Add common keys from the root level.
        $definition['country_code'] = $definitions['country_code'];
        //$definition['parent_id'] = $definitions['parent_id'];
        $definition['subdivision_type'] = $definitions['subdivision_type'];
        $definition['locale'] = $definitions['locale'];
        // Provide defaults.
        if (!isset($definition['code'])) {
            $definition['code'] = $definition['kanji'];
        }

        $subdivision = new Subdivision();
        // Bind the closure to the Subdivision object, giving it access to its
        // protected properties. Faster than both setters and reflection.
        $setValues = \Closure::bind(function ($id, $definition) {
            $this->countryCode = $definition['country_code'];
            $this->id = $id;
            $this->locale = $definition['locale'];
            $this->code = $definition['code'];
            $this->name = $definition['lname'];
            
        }, $subdivision, '\EdgeCreativeMedia\JapanAddressing\Model\Subdivision');
        $setValues($id, $definition);

        return $subdivision;
    }
}