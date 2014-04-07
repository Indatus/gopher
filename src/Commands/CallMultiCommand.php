<?php namespace Indatus\Callbot\Commands;

use Indatus\Callbot\Commands\CallCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class CallMultiCommand extends CallCommand
{
    protected $batchesToRun = array();
    protected $callIds = array();

    protected function configure()
    {
        $this
            ->setName('call:multi')
            ->setDescription('Run multiple batches of calls using multiple call scripts')
            ->addOption('batches', 'b', InputOption::VALUE_REQUIRED, 'Specify which batches to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setBatchesToRun($input->getOption('batches'));

        foreach ($this->batchesToRun as $batch) {

            $script = file_get_contents($batch['script']);

            if (!$this->uploadScript($script)) {

                $output->writeln('<error>Failed to upload script.</error>');
                die;

            }

            foreach ($batch['to'] as $to) {

                $from = $this->getFrom($batch);

                $this->callIds[] = $this->callService->call(
                    $from,
                    $to,
                    $this->uploadName
                );

            }

        }

        if (!empty($this->callIds)) {

            $results = $this->callService->getResults($this->callIds);

            $this->displayResults($output, $results);

        }

    }

    protected function setBatchesToRun($batches)
    {
        $allBatches = $this->config->get('batches');

        if ($batches) {

            $numbers = explode(',', $batches);

            foreach ($numbers as $number) {

                $this->batchesToRun[] = $allBatches[$number - 1];

            }

        } else {

            $this->batchesToRun = $allBatches;

        }
    }

    protected function getFrom($batch)
    {
        if (array_key_exists('from', $batch)) {

            return $batch['from'];

        }

        return $this->config->get('callService.defaultFrom');
    }
}
