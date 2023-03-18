<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BingoGame extends Component
{
 
    public $numbers = [];

    public function mount()
    {
        $this->numbers = range(1, 90);
        shuffle($this->numbers);
        $this->numbers = array_chunk($this->numbers, 5);
    }

    public function render()
    {
        return view('livewire.bingo-game');
    }
}
