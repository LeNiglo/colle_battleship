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
            echo 'Bye !' . PHP_EOL;
            return false;
        }
    }

    private function query(array $args)
    {
        if (is_array($args) && count($args) === 2) {
            if (
                is_array($coord) && count($coord) === 2 &&
                $this->instance->check_coord($args[0], $args[1]) !== false
            ) {
                echo 'full' . PHP_EOL;
                return false;
            } else {
                echo 'empty' . PHP_EOL;
                return true;
            }
        } else {
            echo 'Invalid args' . PHP_EOL;
            return false;
        }
    }

    private function add(array $args)
    {
        if (is_array($args) && count($args) === 2) {
            if ($this->instance->check_coord($args[0], $args[1]) !== false) {
                echo 'A cross already exists at this location' . PHP_EOL;
                return false;
            } else {
                array_push($this->instance->coords, $args);
                return true;
            }
        } else {
            echo 'Invalid args' . PHP_EOL;
            return false;
        }
    }

    private function remove(array $args)
    {
        if (is_array($args) && count($args) === 2) {
            $idx = $this->instance->check_coord($args[0], $args[1]);
            if ($idx === false) {
                echo 'No cross exists at this location' . PHP_EOL;
                return false;
            } else {
                array_splice($this->instance->coords, $idx, $idx + 1);
                return true;
            }
        } else {
            echo 'Invalid args' . PHP_EOL;
            return false;
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

    public function check_coord(int $x, int $y)
    {
        foreach ($this->coords as $idx => $coord) {
            if (is_array($coord) && count($coord) === 2 && $coord[0] == $x && $coord[1] == $y) {
                return $idx;
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
            echo $this->check_coord($this->x, $this->y) !== false ? ' X |' : '   |';
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
