<?php

namespace EdgeCreativeMedia\JapanAddressing\Repository;

use EdgeCreativeMedia\JapanAddressing\Model\Address;

/**
 * Provides the address list.
 *
 */
class AddressRepository implements AddressRepositoryInterface
{
    use DefinitionTranslatorTrait;

    /**
     * The path where address definitions are stored.
     *
     * @var string
     */
    protected $definitionPath;

    /**
     * Address definitions.
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * Creates a AddressRepository instance.
	 *
     * @param string $definitionPath Path to the address definitions.
     *                               Defaults to 'resources/address/'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ?: __DIR__ . '/../../resources/postal_address/';
        
    }

    /**
     * {@inheritdoc}
     */
    public function get($id, $locale = null)
    {	
		//$splitPostCode = splitPostCode($id);
        //$definitions = $this->loadDefinitions($splitPostCode[0]);
        $definitions = $this->loadDefinitions($id);
		//$id = $splitPostCode[0].$splitPostCode[1];
		
        return $this->createAddressFromDefinitions($id, $definitions, $locale);
    }


    /**
     * {@inheritdoc}
     */
    public function getAll($postcode, $locale = null)
    {	
    	$splitPostCode = splitPostCode($postcode);
        $definitions = $this->loadDefinitions($splitPostCode[0]);
        if (empty($definitions)) {
            return [];
        }

        $addresss = [];
        foreach (array_keys($definitions['addresss']) as $id) {
            $addresss[$id] = $this->createAddressFromDefinitions($id, $definitions, $locale);
        }

        return $addresss;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($addressType, $nameType, $locale = null)
    {
        $definitions = $this->loadDefinitions($addressType);
        if (empty($definitions)) {
            return [];
        }

        $list = [];
        foreach ($definitions['address'] as $id => $definition) {
            $definition = $this->translateDefinition($definition, $locale);
            

            $list[$id] = $definition['city_name'];
        }		
        return $list;
    }

    /**
     * Split postcode.
     *
     * @return array The postcode split into array.
     */
    protected function splitPostCode($postcode)
    {
        $splitPostCode = array();
        $postcodeLength = mb_strlen($postcode);
        switch ($postcodeLength) {    
			case 3:
				$splitPostCode[0] = $postcode;
				$splitPostCode[1] = null;						
			break;
			case 7:
				$splitPostCode[0] = mb_substr($postcode, 0, 3);
				$splitPostCode[1] = mb_substr($postcode, 3, 4);
			break;   	
 			case 8:
				$brace_index = mb_strpos($postcode, '-');
				if ($brace_index !== FALSE) {
					$splitPostCode = explode('-', $postcode);   	
				} 							
			break;
			default:
				$splitPostCode = $postcode;
			break;			
    	}
    	return $splitPostCode; 
    }

    /**
     * Loads the address definitions.
     *
     * @return array The address definitions.
     */
    protected function loadDefinitions($postcode)
    {
       
        $countryCode = 'JP';        
        
        $lookupId = $countryCode.'-'.$postcode;
        //$lookupId = $parentId ? $parentId : $countryCode;
        if (isset($this->definitions[$lookupId])) {
            return $this->definitions[$lookupId];
        }    

    	
    	$filename = $this->definitionPath . $lookupId . '.json';
        if ($rawDefinition = @file_get_contents($filename)) {
            $this->definitions = json_decode($rawDefinition, true);
        }

        return $this->definitions;
    }

    /**
     * Creates a address object from the provided definitions.
     *
     * @param int    $id         The address id.
     * @param array  $definition The address definitions.
     * @param string $locale     The locale (e.g. fr-FR).
     *
     * @return Address
     */
    protected function createAddressFromDefinitions($id, array $definitions, $locale)
    {
        if (!isset($definitions['address'][$id])) {
            // No matching definition found.
            return null;
        }

        $definition = $this->translateDefinition($definitions['address'][$id], $locale);
        // Add common keys from the root level.
        $definition['country_code'] = $definitions['country_code'];
        $definition['locale'] = $definitions['locale'];

		
		// Load the parent, if known.
    	/*
    	switch ($addressType) {    
			case 'prefecture':			
				$definition['regionParent'] = $this->getArray($definition['region_code'], 'region');
			break;   	
 			case 'city':
				$definition['regionParent'] = $this->getArray($definition['region_code'], 'region');
				$definition['prefectureParent'] = $this->getArray($definition['prefecture_code'], 'prefecture');	
			break;			
    	}
		*/
		
        $address = new Address();
        // Bind the closure to the Address object, giving it access to its
        // protected properties. Faster than both setters and reflection.
        $setValues = \Closure::bind(function ($id, $definition) {
            
            $this->locale = $definition['locale'];
            $this->countryCode = $definition['country_code'];
            //$this->region = $definition['regionParent'];
            //$this->prefecture = $definition['prefectureParent'];
          
            $this->id = $id;
            $this->regionCode = $definition['region_code'];
            $this->prefectureCode = $definition['prefecture_code'];
            $this->cityCode = $definition['city_code'];            
            $this->postCode = $definition['postcode'];           
            $this->prefectureName = $definition['prefecture_name'];
            $this->cityName = $definition['city_name'];
            $this->wardName = $definition['ward_name'];
            $this->townName = $definition['town_name'];
            $this->buildingName = $definition['building_name'];
            $this->organizationName = $definition['organization_name'];

            
        }, $address, '\EdgeCreativeMedia\JapanAddressing\Model\Address');
        $setValues($id, $definition);
        
        return $address;
    }
}