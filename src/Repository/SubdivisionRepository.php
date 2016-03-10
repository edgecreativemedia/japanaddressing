<?php

namespace EdgeCreativeMedia\JapanAddressing\Repository;

use EdgeCreativeMedia\JapanAddressing\Model\Subdivision;

/**
 * Provides the subdivision list.
 *
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

        $definitions = $this->loadDefinitions($subdivisionType);

        return $this->createSubdivisionFromDefinitions($id, $definitions, $subdivisionType, $locale);
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
            $subdivisions[$id] = $this->createSubdivisionFromDefinitions($id, $definitions, $subdivisionType, $locale);
        }

        return $subdivisions;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($subdivisionType, $nameType, $locale = null)
    {
        $definitions = $this->loadDefinitions($subdivisionType);
        if (empty($definitions)) {
            return [];
        }

        $list = [];
        foreach ($definitions['subdivision'] as $id => $definition) {
            $definition = $this->translateDefinition($definition, $locale);
            
            //load correct list type
    		switch ($nameType) {    
				case 'long':
					$list[$id] = $definition['lname'];
				break;
				case 'short':
					$list[$id] = $definition['sname'];
				break;   	
				default:
					$list[$id] = $definition['lname'];
				break;		
    		}
            
        }		
        return $list;
    }

    /**
     * Loads the subdivision item array.
     *
     * @return array The subdivision item array.
     */
    protected function getArray($id, $subdivisionType, $locale = null)
    {
        $definitions = $this->loadDefinitions($subdivisionType);
        if (empty($definitions)) {
            return [];
        }
        if (!isset($definitions['subdivision'][$id])) {
            // No matching definition found.
            return null;
        }
		$item = [];
        $definition = $this->translateDefinition($definitions['subdivision'][$id], $locale);
        
        // Provide defaults.
        if (!isset($definition['code'])) {
            $definition['code'] = $definition['kanji'];
        }
        
        $item['code'] = $definition['code'];
  
    	//load special items
    	switch ($subdivisionType) {    
			case 'region':				
			break;
			case 'prefecture':
				$item['iso'] = $definition['iso'];
				$item['postal_code_pattern'] = $definition['postal_code_pattern'];
			break;   	
 			case 'city':				
			break;			
    	}        
        $item['lname'] = $definition['lname'];
        $item['sname'] = $definition['sname'];
        $item['kanji'] = $definition['kanji'];
        $item['hiragana'] = $definition['hiragana'];
        $item['romaji'] = $definition['romaji'];           
        
		return $item;
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
				$subdivisionFilename = 'japanregion.json';
			break;
			case 'prefecture':
				$subdivisionFilename = 'japanprefecture.json';
			break;   	
 			case 'city':
				$subdivisionFilename = 'japancity.json';
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
    protected function createSubdivisionFromDefinitions($id, array $definitions, $subdivisionType, $locale)
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
		
		// Load the parent, if known.
    	switch ($subdivisionType) {    
			case 'prefecture':			
				$definition['regionParent'] = $this->getArray($definition['region_code'], 'region');
			break;   	
 			case 'city':
				$definition['regionParent'] = $this->getArray($definition['region_code'], 'region');
				$definition['prefectureParent'] = $this->getArray($definition['prefecture_code'], 'prefecture');	
			break;			
    	}
		
        $subdivision = new Subdivision();
        // Bind the closure to the Subdivision object, giving it access to its
        // protected properties. Faster than both setters and reflection.
        $setValues = \Closure::bind(function ($id, $definition) {
            
            $this->subdivisionType = $definition['subdivision_type'];
            $this->locale = $definition['locale'];
            $this->countryCode = $definition['country_code'];
            $this->region = $definition['regionParent'];
            $this->prefecture = $definition['prefectureParent'];

            $this->postalCodePattern = $definition['postal_code_pattern'];
            $this->iso = $definition['iso'];
            
            $this->id = $id;            
            $this->code = $definition['code'];
            $this->lName = $definition['lname'];
            $this->sName = $definition['sname'];
            $this->kanji = $definition['kanji'];
            $this->hiragana = $definition['hiragana'];
            $this->romaji = $definition['romaji'];
            
        }, $subdivision, '\EdgeCreativeMedia\JapanAddressing\Model\Subdivision');
        $setValues($id, $definition);
        
        return $subdivision;
    }
}