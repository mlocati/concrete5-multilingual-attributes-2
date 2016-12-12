<?php

defined('C5_EXECUTE') or die('Access Denied.');

// Arguments
/* @var Concrete\Core\Multilingual\Service\UserInterface\Flag $flag */
/* @var Concrete\Core\Form\Service\Form $form */
/* @var string[] $textPlaceholders */
/* @var string[] $siteLocales */
/* @var string[] $localeValues */

?>
<label style="font-weight: normal">
    <?php
    echo t('Default value');
    ?>
</label>
<?php
echo $form->text($this->field('defaultValue'), $localeValues[''], ['placeholder' => $textPlaceholders['']]);

foreach ($siteLocales as $siteLocaleID => $siteLocaleName) {
    $chunks = explode('_', $siteLocaleID);
    $checkName = $this->field('setValueFor_'.$siteLocaleID);
    $textName = $this->field('valueFor_'.$siteLocaleID); ?>
    <label style="font-weight: normal">
        <?php
        echo $form->checkbox(
            $checkName,
            '1',
            isset($localeValues[$siteLocaleID]),
            [
                'onchange' => h('if(this.checked)$(this).closest(\'form\').find(\'[name="'.$textName.'"]\').focus();'),
            ]
        );
    echo '&nbsp;';
    $icon = $flag->getFlagIcon(isset($chunks[1]) ? $chunks[1] : '');
    if ($icon) {
        $icon = $icon.' ';
    } else {
        $icon = '';
    }
    echo t(/*i18n: %s is a language name */'Set the value for %s', $icon.$siteLocaleName); ?>
    </label>
    <?php
    echo $form->text(
        $textName,
        $localeValues[$siteLocaleID],
        [
            'placeholder' => $textPlaceholders[$siteLocaleID],
            'oninput' => h('if($.trim(this.value) !== \'\')$(this).closest(\'form\').find(\'[name="'.$checkName.'"]\').prop(\'checked\', true);'),
        ]
    );
}
