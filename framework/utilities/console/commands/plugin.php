<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Plugin commands
 */
class LsConsoleCommandPlugin extends LsConsoleCommandBase
{
    protected $_name;
    protected $_name_under;

    /**
     * Configure command
     */
    protected function configure(): void
    {
        $this->setName('plugin')
            ->setDescription('Plugin commands')
            ->addArgument('action', InputArgument::REQUIRED, 'Action: new')
            ->addArgument('code', InputArgument::OPTIONAL, 'The code of the new plugin. Use only [a-z_] symbols.');
    }

    /**
     * Execute command
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getArgument('action') === 'new') {
            return $this->executeNew((array)$input->getArgument('code'));
        }
        $this->io->error("Unknown action '" . $input->getArgument('action') . "'");
        return Command::FAILURE;
    }

    /**
     * Generate new empty plugin
     *
     * @arg code The code of the new plugin. Use only [a-z_] symbols.
     */
    public function executeNew(array $args)
    {
        if (!($code = reset($args))) {
            $this->io->error('The plugin code is not specified.');
            return Command::FAILURE;
        }

        $this->_name_under = func_underscore($code);
        $this->_name = func_camelize($this->_name_under);

        $path = strtr($this->_name_under, '/\\', DIRECTORY_SEPARATOR);
        $path = Config::Get('path.application.plugins.server') . '/' . $path;
        if (strpos($path, DIRECTORY_SEPARATOR) === false) {
            $path = '.' . DIRECTORY_SEPARATOR . $path;
        }

        $dir = rtrim(realpath(dirname($path)), '\\/');
        if ($dir === false || !is_dir($dir)) {
            $this->io->error("The directory '{$path}' is not valid. Please make sure the parent directory exists.");
            return Command::FAILURE;
        }

        $sourceDir = dirname(__DIR__) . '/protected/plugin';
        if ($sourceDir === false) {
            $this->io->error('Unable to locate the source directory.');
            return Command::FAILURE;
        }

        // Создаем массив файлов для функции копирования
        $aList = $this->buildFileList($sourceDir, $path);

        // Парсим имена плагинов и пересоздаем массив
        foreach ($aList as $sName => $aFile) {
            $sTarget = str_replace('Example', $this->_name, $aFile['target']);
            $sTarget = str_replace('example', $this->_name_under, $sTarget);
            $sNewName = str_replace('Example', $this->_name, $sName);
            $sNewName = str_replace('example', $this->_name_under, $sNewName);
            if ($sName != $sNewName) {
                unset($aList[$sName]);
            }

            $aFile['target'] = $sTarget;
            $aList[$sNewName] = $aFile;
            $aList[$sNewName]['callback'] = [$this, 'generatePlugin'];
        }

        // Копируем файлы
        $this->copyFiles($aList);

        $this->io->writeln("<info>Your plugin has been created successfully under {$path}</info>");
        return Command::SUCCESS;
    }

    /*
     * Парсер выражений в исходниках эталонного плагина
     */
    public function generatePlugin($source, $params)
    {
        $content = file_get_contents($source);
        $content = str_replace('Example', $this->_name, $content);
        $content = str_replace('example', $this->_name_under, $content);
        return $content;
    }
}