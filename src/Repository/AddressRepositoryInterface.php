<?php

namespace EdgeCreativeMedia\JapanAddressing\Repository;

use EdgeCreativeMedia\JapanAddressing\Model\Address;

/**
 * Address repository interface.
 */
interface AddressRepositoryInterface
{
    /**
     * Returns a address instance matching the provided id.
     *
     * @param string $id     The address id.
     * @param string $locale The locale (e.g. fr-FR).
     *
     * @return Address|null The address instance, if found.
     */
    public function get($id, $locale = null);

    /**
     * Returns all address instances for the provided.
     *
     * @param string $countryCode The country code.
     * @param int    $parentId    The parent id.
     * @param string $locale      The locale (e.g. fr-FR).
     *
     * @return Address[] An array of address instances.
     */
    public function getAll($postcode, $locale = null);

    /**
     * Returns a list of addresss.
     *
     * @param string $countryCode The country code.
     * @param int    $parentId    The parent id.
     * @param string $locale      The locale (e.g. fr-FR).
     *
     * @return array An array of address names, keyed by id.
     */
    public function getList($postcode, $nameType, $locale = null);

}