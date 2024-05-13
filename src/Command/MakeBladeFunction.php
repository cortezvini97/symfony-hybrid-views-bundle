<?php

namespace Cortez\SymfonyHybridViews\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name:"blade:make:function",
    description: 'Create create php function for blade',
)]
class MakeBladeFunction extends Command
{

    private $dir;

    public function __construct(string $dir)
    {
        parent::__construct();
        $this->dir = $dir;
    }

    protected function configure()
    {
        $this
            ->addOption('valor', null, InputOption::VALUE_REQUIRED, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        

        $name = $io->ask('Fuction Name:');

        if (strpos($name, ' ') !== false)
        {
            // Substituir espaços por underscores
            $name = str_replace(' ', '_', $name);
        }


        // Verificar se o nome contém caracteres especiais
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            $io->error('The function name contains invalid characters. Use only lowercase letters, numbers, and underscores.');
            return Command::FAILURE;
        }

        if(!file_exists($this->dir))
        {
            mkdir($this->dir);
        }

        $file = $this->dir.DIRECTORY_SEPARATOR.$name.".php";





        if (file_exists($file)) {
            $io->error('Cannot create a file that already exists.');
            return Command::FAILURE;
        }

        $fileContent = <<<EOT
        <?php

        function $name() {
            // Your Logic
        };
        EOT;

        if (!file_put_contents($file, $fileContent)) {
            $io->error('Failed to create the file.');
            return Command::FAILURE;
        }

        $io->success("Success created $file");
        return Command::SUCCESS;
    }
}