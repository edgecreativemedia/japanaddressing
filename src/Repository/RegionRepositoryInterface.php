<?php

namespace EdgeCreativeMedia\JapanLocation\Repository;

use EdgeCreativeMedia\JapanLocation\Model\Subdivision;

/**
 * Region repository interface.
 */
interface JapanRegionRepositoryInterface
{
    /**
     * Returns a region instance matching the provided id.
     *
     * @param string $id     The region id.
     * @param string $locale The locale (e.g. fr-FR).
     *
     * @return Region|null The region instance, if found.
     */
    public function get($id, $locale = null);

    /**
     * Returns all region instances for the provided.
     *
     * @param string $countryCode The country code.
     * @param int    $parentId    The parent id.
     * @param string $locale      The locale (e.g. fr-FR).
     *
     * @return Region[] An array of subdivision instances.
     */
    public function getAll($countryCode, $parentId = null, $locale = null);

    /**
     * Returns a list of regions.
     *
     * @param string $countryCode The country code.
     * @param int    $parentId    The parent id.
     * @param string $locale      The locale (e.g. fr-FR).
     *
     * @return array An array of region names, keyed by id.
     */
    public function getList($countryCode, $parentId = null, $locale = null);

}