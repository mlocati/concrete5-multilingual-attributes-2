<?php
namespace Concrete\Package\MultilingualAttributesII\Attribute\MultilingualText;

use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use MLocati\MultilingualAttributesII\Attribute\MultilingualController;
use MLocati\MultilingualAttributesII\Concrete\Entity\MultilingualAttributeValue;
use Concrete\Core\Multilingual\Service\UserInterface\Flag;

class Controller extends MultilingualController
{
    public $helpers = ['form'];

    protected $akTextPlaceholders;

    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('file-text');
    }

    protected function load()
    {
        $this->akTextPlaceholders = [
            '' => '',
        ];

        $ak = $this->getAttributeKey();
        if ($ak !== null) {
            $type = $ak->getAttributeKeySettings();
            foreach (array_merge([''], $this->getSiteLocaleIdentifiers()) as $localeIdentifier) {
                $data = $type->getSettingsForLocale($localeIdentifier, false);
                if (isset($data['textPlaceholder'])) {
                    $this->akTextPlaceholders[$localeIdentifier] = $data['textPlaceholder'];
                }
            }
        }
    }

    public function type_form()
    {
        $this->load();
        $siteLocales = $this->getSiteLocales();
        $this->set('siteLocales', $this->getSiteLocales());
        $this->set('akTextPlaceholders', $this->akTextPlaceholders);
    }

    public function saveKey($data)
    {
        $type = $this->getAttributeKeySettings();
        $siteLocaleIdentifiers = $this->getSiteLocaleIdentifiers();
        $settings = [];
        foreach (array_merge([''], $siteLocaleIdentifiers) as $localeIdentifier) {
            $localeSettings = null;
            $arrayKey = ($localeIdentifier === '') ? 'akTextPlaceholder_Default' : "akTextPlaceholder_$localeIdentifier";
            if (isset($data[$arrayKey])) {
                $value = trim((string) $data[$arrayKey]);
                if ($value !== '') {
                    $localeSettings = [
                        'textPlaceholder' => $value,
                    ];
                }
            }
            $type->setSettingsForLocale($localeIdentifier, $localeSettings);
        }

        return $type;
    }

    public function form()
    {
        $this->load();
        $this->set('flag', $this->app->make(Flag::class));
        $siteLocales = $this->getSiteLocales();
        $textPlaceholders = $this->akTextPlaceholders;
        if (!isset($textPlaceholders[''])) {
            $textPlaceholders[''] = '';
        }
        foreach (array_keys($siteLocales) as $siteLocaleIdentifier) {
            if (!isset($textPlaceholders[$siteLocaleIdentifier])) {
                $textPlaceholders[$siteLocaleIdentifier] = $textPlaceholders[''];
            }
        }
        $this->set('textPlaceholders', $textPlaceholders);
        $this->set('siteLocales', $siteLocales);
        $localeValues = [];
        foreach (array_merge([''], array_keys($siteLocales)) as $localeIdentifier) {
            $value = null;
            if ($this->attributeValue !== null) {
                $vo = $this->attributeValue->getValueObject();
                if ($vo !== null) {
                    $value = $vo->getValueForLocale($localeIdentifier, false);
                }
            }
            $localeValues[$localeIdentifier] = $value;
        }
        $this->set('localeValues', $localeValues);
    }

    public function createAttributeValueFromRequest()
    {
        return $this->createAttributeValue($this->post());
    }

    public function createAttributeValue($data)
    {
        if ($data instanceof MultilingualAttributeValue) {
            return $data;
        }
        if (!is_array($data)) {
            $data = [];
        }
        $av = new MultilingualAttributeValue();
        $av->setDefaultValue((isset($data['defaultValue']) && is_string($data['defaultValue'])) ? trim($data['defaultValue']) : '');
        foreach ($this->getSiteLocaleIdentifiers() as $siteLocaleID) {
            $value = null;
            if (isset($data["setValueFor_$siteLocaleID"]) && $data["setValueFor_$siteLocaleID"]) {
                $value = '';
                if (isset($data["valueFor_$siteLocaleID"]) && is_string($data["valueFor_$siteLocaleID"])) {
                    $value = trim($data["valueFor_$siteLocaleID"]);
                }
            }
            $av->setValueForLocale($siteLocaleID, $value);
        }

        return $av;
    }
}
