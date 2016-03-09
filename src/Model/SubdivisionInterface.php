<?php

namespace EdgeCreativeMedia\JapanAddressing\Model;

/**
 * Interface for country subdivisons.
 *
 * Subdivisions are hierarchical and can have up to three levels:
 * Administrative Area -> Locality -> Dependent Locality.
 */
interface SubdivisionInterface
{
    /**
     * Gets the subdivision parent.
     *
     * @return SubdivisionInterface|null The parent, or NULL if there is none.
     */
    //public function getParent();

    /**
     * Gets the two-letter country code.
     *
     * This is a CLDR country code, since CLDR includes additional countries
     * for addressing purposes, such as Canary Islands (IC).
     *
     * @return string The two-letter country code.
     */
    //public function getCountryCode();

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
     * Gets the postal code pattern type.
     *
     * @return string|null The postal code pattern type.
     */
    //public function getPostalCodePatternType();

    /**
     * Gets the subdivision children.
     *
     * @return SubdivisionInterface[] The subdivision children.
     */
   // public function getChildren();

    /**
     * Checks whether the subdivision has children.
     *
     * @return bool TRUE if the subdivision has children, FALSE otherwise.
     */
    //public function hasChildren();
    
    /**
     * Gets the subdivision region code.
     *
     * @return string The subdivision region code.
     */
    public function getRegionCode();

    /**
     * Gets the subdivision prefecture code.
     *
     * @return string The subdivision prefecture code.
     */
    public function getPrefectureCode();

    /**
     * Gets the subdivision prefecture iso.
     *
     * @return string The subdivision prefecture iso.
     */
    public function getIso();

}
