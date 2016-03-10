<?php

namespace EdgeCreativeMedia\JapanAddressing\Model;

/**
 * Interface for subdivisons.
 *
 */
interface SubdivisionInterface
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
     * Gets the subdivision type.
     *
     * @return string The subdivision type.
     */
    public function getSubdivisionType();

    /**
     * Gets the subdivision id.
     *
     * @return string The subdivision id.
     */
    public function getId();

    /**
     * Gets the subdivision code.
     *
     * The code will be in the local (non-latin) script if the country uses one.
     *
     * @return string The subdivision code.
     */
    public function getCode();

    /**
     * Gets the subdivision lname.
     *
     * @return string The subdivision lname.
     */
    public function getLname();

    /**
     * Gets the subdivision sname.
     *
     * @return string The subdivision sname.
     */
    public function getSname();

    /**
     * Gets the subdivision kanji.
     *
     * @return string The subdivision kanji.
     */
    public function getKanji();

    /**
     * Gets the subdivision hiragana.
     *
     * @return string The subdivision hiragana.
     */
    public function getHiragana();

    /**
     * Gets the subdivision romaji.
     *
     * @return string The subdivision romaji.
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
     * Gets the subdivision region.
     *
     * @return string The subdivision region.
     */
    public function getRegion();

    /**
     * Gets the subdivision prefecture.
     *
     * @return string The subdivision prefecture.
     */
    public function getPrefecture();

    /**
     * Gets the subdivision prefecture iso.
     *
     * @return string The subdivision prefecture iso.
     */
    public function getIso();

}
