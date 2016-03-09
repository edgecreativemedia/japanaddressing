<?php

namespace EdgeCreativeMedia\JapanAddressing\Model;

//use CommerceGuys\Addressing\Enum\PatternType;
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;

/**
 * Default subdivision implementation.
 *
 * Can be mapped and used by Doctrine.
 * However, due to the high number of subdivisions (>12k) and their high update
 * rate, most implementing applications will want to continue loading them
 * from disk (with a possible caching layer in front), instead of importing
 * them into a database.
 */
class Subdivision implements SubdivisionEntityInterface
{

    /**
     * The subdivision type.
     *
     * @var string
     */
    protected $subdivisionType;

    /**
     * The subdivision id.
     *
     * @var string
     */
    protected $id;

    /**
     * The subdivision code.
     *
     * @var string
     */
    protected $code;

    /**
     * The subdivision lname.
     *
     * @var string
     */
    protected $lName;

    /**
     * The subdivision sname.
     *
     * @var string
     */
    protected $sName;

    /**
     * The subdivision kanji.
     *
     * @var string
     */
    protected $kanji;

    /**
     * The subdivision hiragana.
     *
     * @var string
     */
    protected $hiragana;

    /**
     * The subdivision romaji.
     *
     * @var string
     */
    protected $romaji;

    /**
     * The postal code pattern.
     *
     * @var string
     */
    protected $postalCodePattern;

    /**
     * The postal code pattern type.
     *
     * @var string
     */
    protected $postalCodePatternType;
    
    /**
     * The subdivision region code.
     *
     * @var string
     */
    protected $regionCode;

    /**
     * The subdivision prefecture code.
     *
     * @var string
     */
    protected $prefectureCode;

    /**
     * The subdivision prefecture iso code.
     *
     * @var string
     */
    protected $iso;

    /**
     * The children.
     *
     * @param SubdivisionEntityInterface[]
     */
    //protected $children;

    /**
     * The locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * Creates a Subdivision instance.
     *
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
	*/

    /**
     * {@inheritdoc}
     *
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * {@inheritdoc}
     *
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubdivisionType()
    {
        return $this->subdivisionType;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubdivisionType($subdivisionType)
    {
        $this->subdivisionType = $subdivisionType;

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
    public function setSname($sName)
    {
        $this->sname = $sName;

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
     * {@inheritdoc}
     *
    public function getPostalCodePatternType()
    {
        return $this->postalCodePatternType;
    }

    /**
     * {@inheritdoc}
     *
    public function setPostalCodePatternType($postalCodePatternType)
    {
        PatternType::assertExists($postalCodePatternType);
        $this->postalCodePatternType = $postalCodePatternType;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     *
    public function setChildren(Collection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
    public function hasChildren()
    {
        return !$this->children->isEmpty();
    }

    /**
     * Gets the region code.
     *
     * @return string The region code.
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * Sets the region code.
     *
     * @param string $regionCode The region code.
     */
    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    /**
     * Gets the prefecture code.
     *
     * @return string The prefecture code.
     */
    public function getPrefectureCode()
    {
        return $this->prefectureCode;
    }

    /**
     * Sets the prefecture code.
     *
     * @param string $prefectureCode The prefecture code.
     */
    public function setPrefectureCode($prefectureCode)
    {
        $this->prefectureCode = $prefectureCode;

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
