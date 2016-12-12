<?php
// Arguments
/* @var Concrete\Core\Form\Service\Form $form */
/* @var string[] $siteLocales */
/* @var string[] $akTextPlaceholders */
?>

<fieldset>
    <legend><?php echo t('Multilingual Text Options')?></legend>
    <div class="form-group">
        <?php echo $form->label('akTextPlaceholder_Default', t('Default Placeholder Text')); ?>
        <?php echo $form->text('akTextPlaceholder_Default', $akTextPlaceholders['']); ?>
    </div>
    <?php
    foreach ($siteLocales as $siteLocaleID => $siteLocaleName) {
        ?>
        <div class="form-group">
            <?php echo $form->label('akTextPlaceholder_'.$siteLocaleID, t(/*i18n: %s is a language name */'Placeholder Text in %s', $siteLocaleName)); ?>
            <?php echo $form->text('akTextPlaceholder_'.$siteLocaleID, isset($akTextPlaceholders[$siteLocaleID]) ? $akTextPlaceholders[$siteLocaleID] : ''); ?>
        </div>
        <?php 
    }
    ?>
</fieldset>
