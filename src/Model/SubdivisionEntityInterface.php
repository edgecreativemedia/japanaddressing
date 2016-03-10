<?php

namespace EdgeCreativeMedia\JapanAddressing\Model;

//use Doctrine\Common\Collections\Collection;

interface SubdivisionEntityInterface extends SubdivisionInterface
{


    /**
     * Sets the two-letter country code.
     *
     * @param string $countryCode The two-letter country code.
     *
     * @return self
     */
    public function setCountryCode($countryCode);

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
     * Sets the subdivision region.
     *
     * @param string $region The subdivision region.
     *
     * @return self
     */
    public function setRegion($region);    

    /**
     * Sets the subdivision prefecture .
     *
     * @param string $prefecture The subdivision prefecture.
     *
     * @return self
     */
    public function setPrefecture($prefecture); 

    /**
     * Sets the subdivision prefecture iso.
     *
     * @param string $iso The subdivision prefecture iso.
     *
     * @return self
     */
    public function setIso($iso); 
    
}
