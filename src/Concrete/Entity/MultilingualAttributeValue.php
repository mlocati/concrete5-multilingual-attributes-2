<?php
namespace MLocati\MultilingualAttributesII\Concrete\Entity;

use Doctrine\ORM\Mapping as ORM;
use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;
use Concrete\Core\Localization\Localization;

/**
 * @ORM\Entity
 * @ORM\Table(name="atMultilingualAttribute")
 * @ORM\HasLifecycleCallbacks
 */
class MultilingualAttributeValue extends AbstractValue
{
    /**
     * @ORM\Column(type="json_array")
     */
    protected $valueByLocale = [];

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $searchableText = '';

    /**
     * Set all the values, both the default one and the locale-specific ones.
     *
     * @param array $values
     */
    public function setAllValues(array $values)
    {
        $this->valueByLocale = $values;
    }

    /**
     * Set the default value.
     *
     * @param mixed $value
     */
    public function setDefaultValue($value)
    {
        $this->setValueForLocale('', $value);
    }

    /**
     * Set the value for a specific locale.
     *
     * @param string $localeIdentifier The locale identifier (empty string: default value)
     * @param array $value
     */
    public function setValueForLocale($localeIdentifier, $value)
    {
        if ($value === null) {
            unset($this->valueByLocale[$localeIdentifier]);
        } else {
            $this->valueByLocale[$localeIdentifier] = $value;
        }
    }

    /**
     * Get all the values, both the default one and the locale-specific ones.
     *
     * @return array
     */
    public function getAllValues()
    {
        return $this->valueByLocale;
    }

    public function getDefaultValue()
    {
        return $this->getValueForLocale('');
    }

    /**
     * Get the value for a specific locale.
     *
     * @param string $localeIdentifier The locale identifier (empty string: default value)
     * @param bool $fallbackToDefaultValue Retrieve the default one if no language-specific value is found

     * @return mixed
     */
    public function getValueForLocale($localeIdentifier, $fallbackToDefaultValue = true)
    {
        if (isset($this->valueByLocale[$localeIdentifier])) {
            $result = $this->valueByLocale[$localeIdentifier];
        } elseif ($localeIdentifier !== '' && $fallbackToDefaultValue) {
            $result = $this->getValueForLocale('');
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->getValueForLocale(Localization::activeLocale());
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->setValueForLocale(Localization::activeLocale(), $value);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prepareSearchableText()
    {
        $strings = [];
        if ($this->valueByLocale) {
            foreach ($this->valueByLocale as $s) {
                if (is_string($s)) {
                    $s = trim($s);
                    if (!in_array($s, $strings)) {
                        $strings[] = $s;
                    }
                }
            }
        }
        $this->searchableText = implode("\n\n", $strings);
    }

    public function __toString()
    {
        return (string) $this->getValueForLocale(Localization::activeLocale());
    }
}
