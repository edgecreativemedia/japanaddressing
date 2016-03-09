<?php

namespace EdgeCreativeMedia\JapanAddressing\Model;

//use Doctrine\Common\Collections\Collection;

interface SubdivisionEntityInterface extends SubdivisionInterface
{
    /**
     * Sets the subdivision parent.
     *
     * @param SubdivisionEntityInterface|null $parent The subdivision parent.
     *
     * @return self
     */
    //public function setParent(SubdivisionEntityInterface $parent = null);

    /**
     * Sets the two-letter country code.
     *
     * @param string $countryCode The two-letter country code.
     *
     * @return self
     */
    //public function setCountryCode($countryCode);

    /**
     * Sets the subdivision type.
     *
     * @param string $subdivisionType The subdivision type.
     *
     * @return self
     */
    public function setSubdivisionType($subdivisionType);

    /**
     * Sets the subdivision id.
     *
     * @param string $id The subdivision id.
     *
     * @return self
     */
    public function setId($id);

    /**
     * Sets the subdivision code.
     *
     * @param string $code The subdivision code.
     *
     * @return self
     */
    public function setCode($code);

    /**
     * Sets the subdivision lname.
     *
     * @param string $lName The subdivision lname.
     *
     * @return self
     */
    public function setLname($lName);

    /**
     * Sets the subdivision sname.
     *
     * @param string $sName The subdivision sname.
     *
     * @return self
     */
    public function setSname($sName);

    /**
     * Sets the subdivision kanji.
     *
     * @param string $kanji The subdivision kanji.
     *
     * @return self
     */
    public function setKanji($kanji);

    /**
     * Sets the subdivision hiragana.
     *
     * @param string $hiragana The subdivision hiragana.
     *
     * @return self
     */
    public function setHiragana($hiragana);

    /**
     * Sets the subdivision romaji.
     *
     * @param string $romaji The subdivision romaji.
     *
     * @return self
     */
    public function setRomaji($romaji);

    /**
     * Sets the postal code pattern.
     *
     * @param string $postalCodePattern The postal code pattern.
     *
     * @return self
     */
    public function setPostalCodePattern($postalCodePattern);

    /**
     * Sets the postal code pattern type.
     *
     * @param string $postalCodePatternType The postal code pattern type.
     *
     * @return self
     */
    //public function setPostalCodePatternType($postalCodePatternType);

    /**
     * Sets the subdivision children.
     *
     * @param SubdivisionEntityInterface[] $children The subdivision children.
     *
     * @return self
     */
    //public function setChildren(Collection $children);
    
    
    /**
     * Sets the subdivision region code.
     *
     * @param string $regionCode The subdivision region code.
     *
     * @return self
     */
    public function setRegionCode($regionCode);    

    /**
     * Sets the subdivision prefecture code.
     *
     * @param string $prefectureCode The subdivision prefecture code.
     *
     * @return self
     */
    public function setPrefectureCode($prefectureCode); 

    /**
     * Sets the subdivision prefecture iso.
     *
     * @param string $iso The subdivision prefecture iso.
     *
     * @return self
     */
    public function setIso($iso); 
    
}
