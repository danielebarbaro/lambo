<?php

namespace App;

use Illuminate\Config\Repository;
use Symfony\Component\Process\Process;

class Shell
{
    protected $rootPath;
    protected $projectPath;

    public function __construct(Repository $config)
    {
        $this->rootPath = $config->get('lambo.store.root_path');
        $this->projectPath = $config->get('lambo.store.project_path');
    }

    public function execInRoot($command)
    {
        return $this->exec("cd {$this->rootPath} && $command");
    }

    public function execInProject($command)
    {
        return $this->exec("cd {$this->projectPath} && $command");
    }

    protected function exec($command)
    {
        $process = app()->make(Process::class, [
            'command' => $command,
        ]);

        $process->setTimeout(null);

        // @todo resolve this
        $process->run(function ($type, $buffer) /*use ($showOutput)*/ {
            echo $buffer;

            // if (Process::ERR === $type) {
                // echo 'ERR > ' . $buffer;
            // } elseif ($showOutput) {
                // echo $buffer;
            // }
        });
    }
}