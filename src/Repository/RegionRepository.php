<?php

namespace EdgeCreativeMedia\JapanAddressing\Repository;


/**
 * Provides the region list.
 *
 * Choosing the source at runtime allows integrations (such as the symfony
 * bundle) to stay agnostic about the intl library they need.
 */
class RegionRepository implements RegionRepositoryInterface
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
     * Creates a RegionRepository instance.
	 *
     * @param string $definitionPath Path to the region definitions.
     *                               Defaults to 'resources/subdivision/'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ?: __DIR__ . '/../../resources/subdivision/';
        
    }

    /**
     * {@inheritdoc}
     */
    public function get($id, $locale = null)
    {
        $idParts = explode('-', $id);
        if (count($idParts) < 2) {
            // Invalid id, nothing to load.
            return null;
        }

        // The default ids are constructed to contain the country code
        // and parent id. For "BR-AL-64b095" BR is the country code and BR-AL
        // is the parent id.
        array_pop($idParts);
        $countryCode = $idParts[0];
        $parentId = implode('-', $idParts);
        if ($parentId == $countryCode) {
            $parentId = null;
        }
        $definitions = $this->loadDefinitions($countryCode, $parentId);

        return $this->createRegionFromDefinitions($id, $definitions, $locale);
    }


    /**
     * {@inheritdoc}
     */
    public function getAll($countryCode, $parentId = null, $locale = null)
    {
        $definitions = $this->loadDefinitions();
        if (empty($definitions)) {
            return [];
        }

        $regions = [];
        foreach (array_keys($definitions['regions']) as $id) {
            $regions[$id] = $this->createRegionFromDefinitions($id, $definitions, $locale);
        }

        return $regions;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($countryCode, $parentId = null, $locale = null)
    {
        $definitions = $this->loadDefinitions();
        if (empty($definitions)) {
            return [];
        }

        $list = [];
        foreach ($definitions['regions'] as $id => $definition) {
            $definition = $this->translateDefinition($definition, $locale);
            $list[$id] = $definition['name'];
        }

        return $list;
    }

    /**
     * Loads the region definitions.
     *
     * @return array The region definitions.
     */
    protected function loadDefinitions()
    {
    	$filename = $this->definitionPath . 'japanregion.json';
        if ($rawDefinition = @file_get_contents($filename)) {
            $this->definitions = json_decode($rawDefinition, true);
        }

        return $this->definitions;
    }

    /**
     * Creates a region object from the provided definitions.
     *
     * @param int    $id         The region id.
     * @param array  $definition The region definitions.
     * @param string $locale     The locale (e.g. fr-FR).
     *
     * @return Region
     */
    protected function createRegionFromDefinitions($id, array $definitions, $locale)
    {
        if (!isset($definitions['regions'][$id])) {
            // No matching definition found.
            return null;
        }

        $definition = $this->translateDefinition($definitions['regions'][$id], $locale);
        // Add common keys from the root level.
        $definition['country_code'] = $definitions['country_code'];
        $definition['parent_id'] = $definitions['parent_id'];
        $definition['locale'] = $definitions['locale'];
        // Provide defaults.
        if (!isset($definition['code'])) {
            $definition['code'] = $definition['name'];
        }

        $region = new Region();
        // Bind the closure to the Region object, giving it access to its
        // protected properties. Faster than both setters and reflection.
        $setValues = \Closure::bind(function ($id, $definition) {
            $this->countryCode = $definition['country_code'];
            $this->id = $id;
            $this->code = $definition['code'];
            $this->name = $definition['name'];
            $this->locale = $definition['locale'];
        }, $region, '\CommerceGuys\Addressing\Model\Subdivision');
        $setValues($id, $definition);

        return $region;
    }
}