<?php

namespace EdgeCreativeMedia\JapanAddressing\Model;

//use Doctrine\Common\Collections\Collection;

interface AddressEntityInterface extends AddressInterface
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
     * Sets the address type.
     *
     * @param string $addressType The address type.
     *
     * @return self
     */
    public function setAddressType($addressType);

    /**
     * Sets the address id.
     *
     * @param string $id The address id.
     *
     * @return self
     */
    public function setId($id);

    /**
     * Sets the address code.
     *
     * @param string $code The address code.
     *
     * @return self
     */
    public function setCode($code);

    /**
     * Sets the address lname.
     *
     * @param string $lName The address lname.
     *
     * @return self
     */
    public function setLname($lName);

    /**
     * Sets the address sname.
     *
     * @param string $sName The address sname.
     *
     * @return self
     */
    public function setSname($sName);

    /**
     * Sets the address kanji.
     *
     * @param string $kanji The address kanji.
     *
     * @return self
     */
    public function setKanji($kanji);

    /**
     * Sets the address hiragana.
     *
     * @param string $hiragana The address hiragana.
     *
     * @return self
     */
    public function setHiragana($hiragana);

    /**
     * Sets the address romaji.
     *
     * @param string $romaji The address romaji.
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
     * Sets the address region.
     *
     * @param string $region The address region.
     *
     * @return self
     */
    public function setRegion($region);    

    /**
     * Sets the address prefecture .
     *
     * @param string $prefecture The address prefecture.
     *
     * @return self
     */
    public function setPrefecture($prefecture); 

    /**
     * Sets the address prefecture iso.
     *
     * @param string $iso The address prefecture iso.
     *
     * @return self
     */
    public function setIso($iso); 
    
}
