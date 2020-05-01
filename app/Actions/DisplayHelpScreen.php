<?php

namespace App\Actions;

use App\GeneratesHelpScreens;
use App\Options;

class DisplayHelpScreen
{
    use LamboAction, GeneratesHelpScreens;

    protected $commands = [
        'help-screen' => 'Display this screen', // @todo modify to mention you can ask for help on a specific item
        'edit-config' => 'Edit config file',
        'edit-after' => 'Edit "after" file',
    ];

    public function __invoke()
    {
        $this->line("\n<comment>Usage:</comment>");
        $this->line("  lambo myApplication [arguments]\n");
        $this->line("<comment>Commands (lambo COMMANDNAME):</comment>");

        foreach ($this->commands as $command => $description) {
            $spaces = $this->makeSpaces(strlen($command));
            $this->line("  <info>{$command}</info>{$spaces}{$description}");
        }

        $this->line("\n<comment>Options (lambo myApplication OPTIONS):</comment>");

        foreach ((new Options)->all() as $option) {
            $this->line($this->createCliStringForOption($option));
        }
    }

    public function createCliStringForOption($option)
    {
        if (isset($option['short'])) {
            $flag = '-' . $option['short'] . ', --' . $option['long'];
        } else {
            $flag = '    --' . $option['long'];
        }

        if (isset($option['param_description'])) {
            $flag .= '=' . $option['param_description'];
        }

        $spaces = $this->makeSpaces(strlen($flag));
        $description = $option['cli_description'];

        return "  <info>{$flag}</info>{$spaces}{$description}";
    }

    public function makeSpaces($count)
    {
        return str_repeat(" ", $this->indent - $count);
    }
}
