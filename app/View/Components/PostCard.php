<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PostCard extends Component
{
    public $title;
    public $body;
    public $image;
    public $userName;
    public $userIcon;
    public $favoritesCount;

    public function __construct($title, $body, $image = null, $userName = '', $userIcon = null, $favoritesCount = 0)
    {
        $this->title = $title;
        $this->body = $body;
        $this->image = $image;
        $this->userName = $userName;
        $this->userIcon = $userIcon;
        $this->favoritesCount = $favoritesCount;
    }

    public function render()
    {
        return view('components.post-card');
    }
}
