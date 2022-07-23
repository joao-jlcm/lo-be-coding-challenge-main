<?php
namespace App\Console\Command;

use App\Entity\Log;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use SplFileObject;
use Symfony\Component\Cache;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-logs',
    description: 'Imports logs.',
)]
class ImportLogsCommand extends Command
{
    const CHUNK_SIZE = 50;
    
    const PATTERN = '|^(.+) (.+) (.+) \[(.+)\] "([A-Z]+) (.+) HTTP/(.+)" (\d+)$|i';

    protected static $defaultName = 'app:import-logs';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('filePath', InputArgument::REQUIRED, 'The path of the file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            if (!file_exists($input->getArgument('filePath')))
                throw new Exception('File not found.');
            
            $file = new SplFileObject($input->getArgument('filePath'));
            $file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
            $cache = new Cache\Adapter\FilesystemAdapter();
            $cacheKey = "import-logs." . fileinode($input->getArgument('filePath'));
            $cacheItem = $cache->getItem($cacheKey);
            $currentOffset = $cacheItem->get() ?? 0;

            foreach ($file as $offset => $line) {
                $output->writeln($line);

                // Skip already imported logs
                if ($offset < $currentOffset)
                    continue;

                $parsedLine = $this->parseLine($line);
                $this->saveLog($parsedLine);

                // Flush everything to the database every X inserts
                if ($offset > 0 && ($offset % self::CHUNK_SIZE) == 0) {
                    $cacheItem->set($offset);
                    $cache->save($cacheItem);
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
            }

            // Flush the remaining objects
            $this->entityManager->flush();
            $this->entityManager->clear();

            // Remove offset from cache
            $cache->deleteItem($cacheKey);
            
            return Command::SUCCESS;
        } catch (Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function parseLine($line)
    {
        if (!preg_match(self::PATTERN, $line, $matches))
            throw new \Exception('Error parsing log line: ' . $line);
        
        $parsedLine = array_combine([
            'line',
            'serviceName',
            'clientIp',
            'userId',
            'timestampStr',
            'httpVerb',
            'httpPath',
            'httpVersion',
            'responseCode'
        ], $matches);

        array_walk($parsedLine, function (&$item) {
            if ($item == '-')
                $item = null;
        });

        $parsedLine['timestamp'] = new DateTime($parsedLine['timestampStr']);
        unset($parsedLine['timestampStr']);
        unset($parsedLine['line']);
        ksort($parsedLine);

        return $parsedLine;
    }

    protected function saveLog($parsedLine): Log
    {
        $log = new Log();
        $log->setHttpPath($parsedLine['httpPath']);
        $log->setHttpVerb($parsedLine['httpVerb']);
        $log->setHttpVersion($parsedLine['httpVersion']);
        $log->setResponseCode($parsedLine['responseCode']);
        $log->setServiceName($parsedLine['serviceName']);
        $log->setTimestamp($parsedLine['timestamp']);
        $this->entityManager->persist($log);

        return $log;
    }
}