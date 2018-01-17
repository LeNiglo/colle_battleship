<?php

/**
*
*/
class Parser
{
    private $instance = null;

    public function __construct(Battleship &$instance)
    {
        $this->instance = $instance;
    }

    public function parse(string $input)
    {
        $input = trim($input);
        $space_strpos = strpos($input, ' ');

        $cmd = $space_strpos === false ? $input : substr($input, 0, $space_strpos);
        $args = $space_strpos === false ? null : substr($input, $space_strpos + 1);

        switch ($cmd) {
            case 'query':
            eval("\$args = $args;");
            $this->query($args);
            return true;

            case 'add':
            eval("\$args = $args;");
            $this->add($args);
            return true;

            case 'remove':
            eval("\$args = $args;");
            $this->remove($args);
            return true;

            case 'render':
            case 'display':
            $this->instance->display();
            return true;

            case 'quit':
            case 'exit':
            default:
            echo "Bye !\n";
            return false;
        }
    }

    private function query(array $args, bool $display = true)
    {
        foreach ($this->instance->coords as $index => $coord) {
            if (
                is_array($coord) && count($coord) === 2 && is_array($args) && count($args) === 2 &&
                $coord[0] == $args[0] && $coord[1] == $args[1]
            ) {
                if ($display === true) {
                    echo "full\n";
                }
                return $index;
            }
        }
        if ($display === true) {
            echo "empty\n";
        }
        return false;
    }

    private function add(array $args)
    {
        if ($this->query($args, false) !== false) {
            echo "A cross already exists at this location\n";
            return false;
        } else {
            array_push($this->instance->coords, $args);
            return true;
        }
    }

    private function remove(array $args)
    {
        $idx = $this->query($args, false);
        if ($idx === false) {
            echo "No cross exists at this location\n";
            return false;
        } else {
            array_splice($this->instance->coords, $idx, $idx + 1);
            return true;
        }
    }
}


/**
*
*/
class Battleship
{
    private $height;
    private $width;
    public $coords;

    private $x = 0;
    private $y = 0;

    public function __construct($width, $height, $coords = [])
    {
        $this->height = $height;
        $this->width = $width;
        $this->coords = $coords;
    }

    private function check_coord()
    {
        foreach ($this->coords as $coord) {
            if (is_array($coord) && count($coord) === 2 && $coord[0] == $this->x && $coord[1] == $this->y) {
                return true;
            }
        }
        return false;
    }

    private function draw_vr()
    {
        for ($this->x = 0; $this->x < $this->width; $this->x++) {
            if ($this->x === 0) {
                echo '|';
            }
            echo $this->check_coord() ? ' X |' : '   |';
        }
        if ($this->width > 0) {
            echo PHP_EOL;
        }
    }

    private function draw_hr()
    {
        for ($this->x = 0; $this->x < $this->width; $this->x++) {
            if ($this->x === 0) {
                echo '+';
            }
            echo '---+';
        }
        if ($this->width > 0) {
            echo PHP_EOL;
        }
    }

    public function display()
    {
        for ($this->y = 0; $this->y < $this->height; $this->y++) {
            if ($this->y === 0) {
                $this->draw_hr();
            }
            $this->draw_vr();
            $this->draw_hr();
        }
    }

    public function prompt()
    {
        $this->display();
        $parser = new Parser($this);
        do {
            $input = readline('$> ');
            $ret = $parser->parse($input);
        } while ($input && $ret);
    }
}


function colle(int $x, int $y, array $coords = [])
{
    $battleship = new Battleship($x, $y, $coords);

    $battleship->prompt();
}
