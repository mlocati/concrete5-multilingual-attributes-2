<?php
namespace Concrete\Package\MultilingualAttributesII;

use Concrete\Core\Package\Package;
use Concrete\Core\Backup\ContentImporter;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'multilingual_attributes_i_i';

    protected $appVersionRequired = '8.0.0RC2';

    protected $pkgVersion = '1.0.0';

    protected $pkgAutoloaderRegistries = [
        'src' => 'MLocati\\MultilingualAttributesII',
    ];

    public function getPackageName()
    {
        return t('Multilingual Attributes II');
    }

    public function getPackageDescription()
    {
        return t('Add support to attributes with language-specific values');
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installReal('', $pkg);
    }

    public function upgrade()
    {
        $currentVersion = $this->getPackageVersion();
        parent::upgrade();
        $this->installReal($currentVersion, $this);
    }

    private function installReal($fromVersion, $pkg)
    {
        $contentImporter = $this->app->make(ContentImporter::class);
        $contentImporter->importContentFile($this->getPackagePath() . '/config/install.xml');
    }
}
