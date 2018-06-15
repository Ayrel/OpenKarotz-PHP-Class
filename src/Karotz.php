<?php

namespace Ayrel\Karotz;

use Symfony\Component\Process\PhpExecutableFinder;

class Karotz extends OpenKarotz
{
    public function isAlive()
    {
        return sizeof($this->getStatus()) > 0;
    }

    public function isSleeping()
    {
        $status = $this->getStatus();

        return boolval($status['sleep']);
    }

    public function start()
    {
        if ($this->isSleeping()) {
            $this->wakeUp(true);
        }
    }

    public function dit($text)
    {
        $result = $this->say($text, 1, false);
        if (!$result['played']) {
            var_dump($result);
            return false;
        }

        return true;
    }

    public function playFile()
    {
        $process = $this->createServerProcess();
        var_dump($process);
    }

    private function createServerProcess()
    {
        $finder = new PhpExecutableFinder();
        if (false === $binary = $finder->find(false)) {
            throw new \RuntimeException('Unable to find the PHP binary.');
        }

        $process = new Process(array_merge(
                array($binary),
                $finder->findArguments(),
                array('-dvariables_order=EGPCS', '-S', "0.0.0.0:1309")
        ));
        $process->setWorkingDirectory(__DIR__);
        $process->setTimeout(null);

        return $process;
    }
}
