<?php
namespace MLocati\MultilingualAttributesII\Concrete\Entity;

use Doctrine\ORM\Mapping as ORM;
use Concrete\Core\Entity\Attribute\Key\Settings\Settings;

/**
 * @ORM\Entity
 * @ORM\Table(name="atMultilingualAttributeSettings")
 */
class MultilingualAttributeSettings extends Settings
{
    /**
     * @ORM\Column(type="json_array")
     */
    protected $settingsByLocale = [];

    /**
     * Set all the settings, both the default ones and the locale-specific ones.
     *
     * @param array $settings
     */
    public function setAllSettings(array $settings)
    {
        $this->settingsByLocale = $settings;
    }

    /**
     * Set the default settings.
     *
     * @param array $settings
     */
    public function setDefaultSettings(array $settings = null)
    {
        $this->setSettingsForLocale('', $settings);
    }

    /**
     * Set the settings for a specific locale.
     *
     * @param string $localeIdentifier The locale identifier (empty string: default settings)
     * @param array $settings
     */
    public function setSettingsForLocale($localeIdentifier, array $settings = null)
    {
        if ($settings === null) {
            unset($this->settingsByLocale[$localeIdentifier]);
        } else {
            $this->settingsByLocale[$localeIdentifier] = $settings;
        }
    }

    /**
     * Get all the settings, both the default ones and the locale-specific ones.
     *
     * @return array
     */
    public function getAllSettings()
    {
        return $this->settingsByLocale;
    }

    public function getDefaultSettings()
    {
        return $this->getSettingsForLocale('');
    }

    /**
     * Get the settings for a specific locale.
     *
     * @param string $localeIdentifier The locale identifier (empty string: default settings)
     * @param bool $fallbackToDefaultSettings Retrieve the default ones if no language-specific settings are found

     * @return array
     */
    public function getSettingsForLocale($localeIdentifier, $fallbackToDefaultSettings = true)
    {
        if (isset($this->settingsByLocale[$localeIdentifier])) {
            $result = $this->settingsByLocale[$localeIdentifier];
        } elseif ($localeIdentifier !== '' && $fallbackToDefaultSettings) {
            $result = $this->getSettingsForLocale('');
        } else {
            $result = [];
        }

        return $result;
    }
}
