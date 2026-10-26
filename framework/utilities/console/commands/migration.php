<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Migration commands
 */
class LsConsoleCommandMigration extends LsConsoleCommandBase
{
    /**
     * Configure command
     */
    protected function configure(): void
    {
        $this->setName('migration')
            ->setDescription('Migration commands')
            ->addArgument('action', InputArgument::REQUIRED, 'Action: create|migrate')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the new migration')
            ->addOption('plugin', null, InputOption::VALUE_REQUIRED, 'Plugin code')
            ->addOption('dry', null, InputOption::VALUE_NONE, 'Dry run');
    }

    /**
     * Execute command
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        switch ($input->getArgument('action')) {
            case 'create':
                return $this->executeCreate((array)$input->getArgument('name'), $input->getOption('plugin'));
            case 'migrate':
                return $this->executeMigrate($input);
            default:
                $this->io->error("Unknown action '" . $input->getArgument('action') . "'");
                return Command::FAILURE;
        }
    }

    public function executeCreate(array $args, $sPlugin = null)
    {
        if (!($sName = reset($args))) {
            $this->io->error('The name migration is not specified.');
            return Command::FAILURE;
        }
        $sNameUnderscore = func_underscore($sName);
        if ($sPlugin) {
            $sPlugin = func_underscore($sPlugin);
            $sPath = Config::Get('path.application.plugins.server') . DIRECTORY_SEPARATOR . $sPlugin . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR;
        } else {
            $sPath = Config::Get('path.root.server') . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR;
        }

        $sDirParent = dirname($sPath);
        if (!is_dir($sDirParent)) {
            $this->io->error("The directory '{$sDirParent}' is not valid.");
            return Command::FAILURE;
        }
        if (!is_dir($sPath)) {
            @mkdir($sPath, 0755, true);
        }
        if (!is_dir($sPath)) {
            $this->io->error("Can not create 'migrations' directory.");
            return Command::FAILURE;
        }

        $sVersion = (string)time();
        $sFileName = $sVersion . '_' . $sNameUnderscore . '.php';
        $sClassName = 'Migration_' . $sVersion;
        if ($sPlugin) {
            $sClassName = 'Plugin' . func_camelize($sPlugin) . '_' . $sClassName;
        }
        $sSourceFile = dirname(__DIR__) . '/protected/migration/migration.php';
        $this->copyFiles([
            [
                'source'   => $sSourceFile,
                'target'   => $sPath . $sFileName,
                'callback' => function ($sSource, $aParams) use ($sClassName) {
                    $sContent = file_get_contents($sSource);
                    $sContent = str_replace('{{className}}', $sClassName, $sContent);
                    return $sContent;
                }
            ]
        ]);

        $this->io->writeln("<info>Your migration has been created successfully: " . $sPath . $sFileName . "</info>");
        return Command::SUCCESS;
    }

    public function executeMigrate(InputInterface $input)
    {
        $bIsDry = $input->getOption('dry');
        $sPlugin = $input->getOption('plugin');
        /**
         * Список каталогов, где могут быть миграции
         */
        $aLocations = Engine::getInstance()->Migration_GetLocationsForMigration($sPlugin);
        if (!$aLocations) {
            $this->io->writeln("<info>Not found migrate locations</info>");
            return Command::SUCCESS;
        }
        $this->io->writeln("<info>Found migrate locations:</info>");
        foreach ($aLocations as $aLocation) {
            $this->io->writeln("<comment>{$aLocation['path']}</comment>");
        }

        if (!Engine::getInstance()->Database_IsTableExists('prefix_migration')) {
            Engine::getInstance()->Database_ExportSQLQuery('CREATE TABLE `prefix_migration` (
              `id` int(11) NOT NULL,
              `plugin` varchar(30) DEFAULT NULL,
              `version` varchar(15) NOT NULL,
              `date_create` datetime NOT NULL,
              `state` tinyint(1) NOT NULL DEFAULT \'1\'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            
            ALTER TABLE `prefix_migration`
              ADD PRIMARY KEY (`id`),
              ADD KEY `plugin` (`plugin`),
              ADD KEY `state` (`state`),
              ADD KEY `version` (`version`);
            
            ALTER TABLE `prefix_migration`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
        }

        $aMigrationCompleted = [];
        foreach ($aLocations as $aLocation) {
            $aMigrationFiles = Engine::getInstance()->Migration_GetNewMigrationFiles($aLocation);
            $iCountNew = count($aMigrationFiles);
            $this->io->writeln("<info>Location {$aLocation['path']}: " . $iCountNew . ' new migrations</info>');
            if ($aMigrationFiles) {
                foreach ($aMigrationFiles as $sMigrationFile) {
                    $iTime1 = microtime(true);
                    $this->io->writeln("<comment>Migrate {$sMigrationFile}</comment>");
                    $bRes = true;
                    if ($bIsDry) {
                        sleep(1);
                    } else {
                        $bRes = Engine::getInstance()->Migration_RunMigrationUp($aLocation, $sMigrationFile);
                    }
                    if ($bRes) {
                        $iTime2 = microtime(true);
                        $fSeconds = round(($iTime2 - $iTime1), 2);
                        $this->io->writeln("<comment>Migrated {$sMigrationFile}: {$fSeconds} sec</comment>");

                        $aMigrationCompleted[] = [
                            'location' => $aLocation,
                            'file'     => $sMigrationFile
                        ];
                    } else {
                        /**
                         * Откатываем все обратно
                         */
                        if ($aMigrationCompleted) {
                            $i = count($aMigrationCompleted);
                            while ($i) {
                                $aData = $aMigrationCompleted[--$i];
                                Engine::getInstance()->Migration_RunMigrationDown($aData['location'], $aData['file']);
                            }
                        }

                        $this->io->error("Failed");
                        return Command::FAILURE;
                    }
                }
            }
        }

        $this->io->writeln("<info>All migrate successfully</info>");
        return Command::SUCCESS;
    }
}