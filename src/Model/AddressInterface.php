<?php

namespace EdgeCreativeMedia\JapanAddressing\Model;

/**
 * Interface for subdivisons.
 *
 */
interface AddressInterface
{
    /**
     * Gets the two-letter country code.
     *
     * This is a CLDR country code, since CLDR includes additional countries
     * for addressing purposes, such as Canary Islands (IC).
     *
     * @return string The two-letter country code.
     */
    public function getCountryCode();

    /**
     * Gets the address type.
     *
     * @return string The address type.
     */
    public function getAddressType();

    /**
     * Gets the address id.
     *
     * @return string The address id.
     */
    public function getId();

    /**
     * Gets the address code.
     *
     * The code will be in the local (non-latin) script if the country uses one.
     *
     * @return string The address code.
     */
    public function getCode();

    /**
     * Gets the address lname.
     *
     * @return string The address lname.
     */
    public function getLname();

    /**
     * Gets the address sname.
     *
     * @return string The address sname.
     */
    public function getSname();

    /**
     * Gets the address kanji.
     *
     * @return string The address kanji.
     */
    public function getKanji();

    /**
     * Gets the address hiragana.
     *
     * @return string The address hiragana.
     */
    public function getHiragana();

    /**
     * Gets the address romaji.
     *
     * @return string The address romaji.
     */
    public function getRomaji();

    /**
     * Gets the postal code pattern.
     *
     * This is a regular expression pattern used to validate postal codes.
     *
     * @return string|null The postal code pattern.
     */
    public function getPostalCodePattern();
    
    /**
     * Gets the address region.
     *
     * @return string The address region.
     */
    public function getRegion();

    /**
     * Gets the address prefecture.
     *
     * @return string The address prefecture.
     */
    public function getPrefecture();

    /**
     * Gets the address prefecture iso.
     *
     * @return string The address prefecture iso.
     */
    public function getIso();

}
