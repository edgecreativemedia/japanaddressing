<?php

namespace EdgeCreativeMedia\JapanLocation\Repository;

use EdgeCreativeMedia\JapanLocation\Model\Subdivision;

/**
 * Subdivision repository interface.
 */
interface SubdivisionRepositoryInterface
{
    /**
     * Returns a subdivision instance matching the provided id.
     *
     * @param string $id     The subdivision id.
     * @param string $locale The locale (e.g. fr-FR).
     *
     * @return Subdivision|null The subdivision instance, if found.
     */
    public function get($id, $subdivisionType, $locale = null);

    /**
     * Returns all subdivision instances for the provided.
     *
     * @param string $countryCode The country code.
     * @param int    $parentId    The parent id.
     * @param string $locale      The locale (e.g. fr-FR).
     *
     * @return Subdivision[] An array of subdivision instances.
     */
    public function getAll($subdivisionType, $locale = null);

    /**
     * Returns a list of subdivisions.
     *
     * @param string $countryCode The country code.
     * @param int    $parentId    The parent id.
     * @param string $locale      The locale (e.g. fr-FR).
     *
     * @return array An array of subdivision names, keyed by id.
     */
    public function getList($subdivisionType, $locale = null);

}