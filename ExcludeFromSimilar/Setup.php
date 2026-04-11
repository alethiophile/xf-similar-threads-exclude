<?php

namespace QQ\ExcludeFromSimilar;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;

class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1()
    {
        $this->schemaManager()->alterTable('xf_thread', function (Alter $table)
        {
            $table->addColumn('qq_exclude_from_similar', 'tinyint', 3)
                ->setDefault(0);
        });
    }

    public function uninstallStep1()
    {
        $this->schemaManager()->alterTable('xf_thread', function (Alter $table)
        {
            $table->dropColumns('qq_exclude_from_similar');
        });
    }
}
