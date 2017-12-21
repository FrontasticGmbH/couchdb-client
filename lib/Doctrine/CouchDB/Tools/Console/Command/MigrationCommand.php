<?php

namespace Doctrine\CouchDB\Tools\Console\Command;


use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Command\Command;

class MigrationCommand extends Command
{
    protected function configure()
    {
        $this->setName('couchdb:migrate')
             ->setDescription('Execute a migration in CouchDB.')
             ->setDefinition(array(
                new InputArgument('class', InputArgument::REQUIRED, 'Migration class name', null),
        ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $className = $input->getArgument('class');
        if (!class_exists($className) || !in_array('Doctrine\CouchDB\Tools\Migrations\AbstractMigration', class_parents($className))) {
            throw new \InvalidArgumentException("class passed to command has to extend 'Doctrine\CouchDB\Tools\Migrations\AbstractMigration'");
        }
        $migration = new $className($this->getHelper('couchdb')->getCouchDBClient());
        $migration->execute();

        $output->writeln("Migration was successfully executed!");
    }
}
