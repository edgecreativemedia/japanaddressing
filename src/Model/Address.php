<?php

namespace EdgeCreativeMedia\JapanAddressing\Model;

//use CommerceGuys\Addressing\Enum\PatternType;
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;

/**
 * Default address implementation.
 *
 * Can be mapped and used by Doctrine.
 * However, due to the high number of addresss (>12k) and their high update
 * rate, most implementing applications will want to continue loading them
 * from disk (with a possible caching layer in front), instead of importing
 * them into a database.
 */
class Address implements AddressEntityInterface
{

 
     /**
     * The address type.
     *
     * @var string
     */
    protected $addressType;

    /**
     * The locale.
     *
     * @var string
     */
    protected $locale;

     /**
     * The country code.
     *
     * @var string
     */
    protected $countryCode;

    /**
     * The address region code.
     *
     * @var string
     */
    protected $region;

    /**
     * The address prefecture code.
     *
     * @var string
     */
    protected $prefecture;
 
     /**
     * The address prefecture iso code.
     *
     * @var string
     */
    protected $iso;
 
    /**
     * The postal code pattern.
     *
     * @var string
     */
    protected $postalCodePattern;
    


    /**
     * The address id.
     *
     * @var string
     */
    protected $id;

    /**
     * The address code.
     *
     * @var string
     */
    protected $code;

    /**
     * The address lname.
     *
     * @var string
     */
    protected $lName;

    /**
     * The address sname.
     *
     * @var string
     */
    protected $sName;

    /**
     * The address kanji.
     *
     * @var string
     */
    protected $kanji;

    /**
     * The address hiragana.
     *
     * @var string
     */
    protected $hiragana;

    /**
     * The address romaji.
     *
     * @var string
     */
    protected $romaji;



    /**
     * The postal code pattern type.
     *
     * @var string
     */
   // protected $postalCodePatternType;
    




    /**
     * The children.
     *
     * @param AddressEntityInterface[]
     */
    //protected $children;



    /**
     * Creates a Address instance.
     *
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
	*/

    /**
     * {@inheritdoc}
     *
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     *
    public function setParent(AddressEntityInterface $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressType()
    {
        return $this->addressType;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * {@inheritdoc}
     */
    public function setLname($lName)
    {
        $this->lname = $lName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSname()
    {
        return $this->sname;
    }

    /**
     * {@inheritdoc}
     */
    public function setSname($sName)
    {
        $this->sname = $sName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKanji()
    {
        return $this->kanji;
    }

    /**
     * {@inheritdoc}
     */
    public function setKanji($kanji)
    {
        $this->kanji = $kanji;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHiragana()
    {
        return $this->hiragana;
    }

    /**
     * {@inheritdoc}
     */
    public function setHiragana($hiragana)
    {
        $this->hiragana = $hiragana;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRomaji()
    {
        return $this->romaji;
    }

    /**
     * {@inheritdoc}
     */
    public function setRomaji($romaji)
    {
        $this->romaji = $romaji;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostalCodePattern()
    {
        return $this->postalCodePattern;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostalCodePattern($postalCodePattern)
    {
        $this->postalCodePattern = $postalCodePattern;

        return $this;
    }


    /**
     * Gets the region.
     *
     * @return string The region.
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Sets the region.
     *
     * @param string $region The region.
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Gets the prefecture.
     *
     * @return string The prefecture.
     */
    public function getPrefecture()
    {
        return $this->prefecture;
    }

    /**
     * Sets the prefecture.
     *
     * @param string $prefecture The prefecture.
     */
    public function setPrefecture($prefecture)
    {
        $this->prefecture = $prefecture;

        return $this;
    }

    /**
     * Gets the prefecture iso.
     *
     * @return string The prefecture iso.
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Sets the prefecture iso.
     *
     * @param string $iso The prefecture iso.
     */
    public function setIso($iso)
    {
        $this->iso = $iso;

        return $this;
    }

    /**
     * Gets the locale.
     *
     * @return string The locale.
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the locale.
     *
     * @param string $locale The locale.
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
