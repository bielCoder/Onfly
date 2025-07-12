<?php
namespace App\Classes\Utilities;
class AuthCode {

    private $target = [1,2,3,4,5,6,7,8,9];

    public function random()
    {
        $shuffled = $this->target;
        shuffle($shuffled);
        $selected = array_slice($shuffled, 0, 6);
        return implode('', $selected); // ex: "2743"
    }

}
