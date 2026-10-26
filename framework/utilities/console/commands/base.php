<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Base class for commands
 */
abstract class LsConsoleCommandBase extends Command
{
    /** @var SymfonyStyle */
    protected $io;
    /** @var ProgressBar|null */
    protected $progress;

    /**
     * Инициализация вывода
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /*
     * Создает массив файлов используемый при копировании
     */
    public function buildFileList($sSourceDir, $sTargetDir, $sBaseDir = '')
    {
        $aList = [];
        $handle = opendir($sSourceDir);
        while (($sFile = readdir($handle)) !== false) {
            if ($sFile === '.' || $sFile === '..' || $sFile === '.svn' || $sFile === '.gitignore') {
                continue;
            }

            $sSourcePath = $sSourceDir . DIRECTORY_SEPARATOR . $sFile;
            $sTargetPath = $sTargetDir . DIRECTORY_SEPARATOR . $sFile;

            $sName = ($sBaseDir === '') ? $sFile : $sBaseDir . '/' . $sFile;

            // Строим массив с ключем в виде имени файла или папки, пути к исходнику и пути назначения
            $aList[$sName] = [
                'source' => $sSourcePath,
                'target' => $sTargetPath
            ];

            // Если директория то рекурсивно получаем массив его содержимого и объединяем с главным
            if (is_dir($sSourcePath)) {
                $aList = array_merge($aList, $this->buildFileList($sSourcePath, $sTargetPath, $sName));
            }
        }
        closedir($handle);
        return $aList;
    }

    /*
     * Копирование файлов
     */
    public function copyFiles($fileList)
    {
        $this->progress = new ProgressBar($this->io, count($fileList));
        $this->progress->start();
        $overwriteAll = false;
        foreach ($fileList as $name => $file) {
            $this->progress->advance();
            $source = strtr($file['source'], '/\\', DIRECTORY_SEPARATOR);
            $target = strtr($file['target'], '/\\', DIRECTORY_SEPARATOR);

            $callback = $file['callback'] ?? null;
            $params = $file['params'] ?? null;

            if (is_dir($source)) {
                // Проверяем существует ли директория или досоздаем папки
                $this->ensureDirectory($target);
                continue;
            }

            // Если существует коллбэк то вызываем его
            if ($callback !== null) {
                $content = call_user_func($callback, $source, $params);
            } // Либо отдаем содержимое исходника без изменений
            else {
                $content = file_get_contents($source);
            }

            // Если файл в папке назначения уже существует
            if (is_file($target)) {
                // Если содержимое старого и нового файла совпадают
                if ($content === file_get_contents($target)) {
                    $this->writeProgress("  unchanged $name");
                    continue;
                }

                // Если мы выбрали перезапись в ветке false
                if ($overwriteAll) {
                    $this->writeProgress("  overwrite $name");
                } else {
                    $this->progress->clear();
                    $this->io->writeln("      exist $name");
                    $answer = $this->io->ask('            ...overwrite? [Yes|No|All|Quit] ', 'No');
                    if (!strncasecmp($answer, 'q', 1)) {
                        $this->io->writeln('<error>Copy files break</error>');
                        $this->progress->display();
                        return;
                    } else {
                        if (!strncasecmp($answer, 'y', 1) || !strncasecmp($answer, 'a', 1)) {
                            $overwriteAll = !strncasecmp($answer, 'a', 1);
                            $this->io->writeln("  overwrite $name");
                        } else {
                            $this->io->writeln("       skip $name");
                            $this->progress->display();
                            continue;
                        }
                    }
                    $this->progress->display();
                }
            } // Если файла еще не существует
            else {
                // Досоздаем папки в случае отсутствия
                $this->ensureDirectory(dirname($target));
                $this->writeProgress("   generate $name");
            }

            // Создаем файл и записываем в него содержимое
            file_put_contents($target, $content);
        }
        $this->progress->finish();
        $this->io->newLine();
    }

    /**
     * Вывод строки без затирания активного прогресс-бара
     * @param string $text
     */
    protected function writeProgress($text)
    {
        $this->progress->clear();
        $this->io->writeln($text);
        $this->progress->display();
    }

    /**
     * Создает родительские папки если они не существуют
     * @param string $directory
     */
    public function ensureDirectory($directory)
    {
        if (!is_dir($directory)) {
            $this->ensureDirectory(dirname($directory));
            $this->writeProgress("      mkdir " . strtr($directory, '\\', '/'));
            mkdir($directory);
        }
    }
}