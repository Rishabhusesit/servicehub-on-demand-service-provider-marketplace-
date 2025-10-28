<?php

namespace App\Http\Controllers;

use App\Traits\SupportTicketManager;

class TicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        parent::__construct();

        if (auth('web')->check()) {
            $this->layout       = 'frontend';
            $this->redirectLink = 'ticket.view';
            $this->userType     = 'user';
            $this->column       = 'user_id';
            $this->user         = auth()->user();
            if ($this->user) {
                $this->layout = 'master';
            }
        } elseif (auth('provider')->check()) {
            $this->layout       = 'frontend';
            $this->redirectLink = 'ticket.view';
            $this->userType     = 'provider';
            $this->column       = 'provider_id';
            $this->user = auth('provider')->user();
            if ($this->user) {
                $this->layout = 'master';
            }
        }
        else {
            $this->layout       = 'frontend';
            $this->redirectLink = 'ticket.view';
            $this->userType     = 'user';
            $this->column       = 'user_id';
            $this->user         = null;
        }
    }
}
