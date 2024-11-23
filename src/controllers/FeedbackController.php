<?php

namespace App\Controllers;

use Core\Controller;
use Core\Request;

class FeedbackController extends Controller
{
    function defineEndpoints(): void
    {
        $this->registerEndpoint('POST', '/api/feedback', 'feedback');
    }

    function feedback(Request $req): void
    {
        $this->ok(
            ["message" => "All Good!"]
        );
    }
}
