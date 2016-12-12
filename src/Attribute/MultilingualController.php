<?php
namespace MLocati\MultilingualAttributesII\Attribute;

use Concrete\Core\Attribute\Controller;
use Concrete\Core\Multilingual\Page\Section\Section;
use Punic\Language;
use Punic\Comparer;
use MLocati\MultilingualAttributesII\Concrete\Entity\MultilingualAttributeSettings;
use MLocati\MultilingualAttributesII\Concrete\Entity\MultilingualAttributeValue;

/**
 * @method MultilingualAttributeSettings getAttributeKeySettings()
 */
class MultilingualController extends Controller
{
    /**
     * Get the locale identifiers for the languages used in the site.
     *
     * @return string[]
     */
    public function getSiteLocaleIdentifiers()
    {
        static $result;

        if (!isset($result)) {
            $localeIDs = [];
            foreach (Section::getList() as $multilingualSection) {
                $localeIDs[] = $multilingualSection->getLocale();
            }
            $result = $localeIDs;
        }

        return $result;
    }

    /**
     * Get the locale identifiers for the languages used in the site.
     *
     * @return string[]
     */
    public function getSiteLocales()
    {
        $locales = [];
        foreach ($this->getSiteLocaleIdentifiers() as $localeIdentifier) {
            $locales[$localeIdentifier] = Language::getName($localeIdentifier);
        }
        $comparer = new Comparer();
        $comparer->sort($locales, true);

        return $locales;
    }

    protected function retrieveAttributeKeySettings()
    {
        return $this->entityManager->find(MultilingualAttributeSettings::class, $this->attributeKey);
    }

    public function createAttributeKeySettings()
    {
        return new MultilingualAttributeSettings();
    }

    public function getAttributeValueObject()
    {
        return $this->entityManager->find(MultilingualAttributeValue::class, $this->attributeValue->getGenericValue());
    }
}
