<?php

namespace App\Http\Controllers;

use App\Services\ActionService;
use App\Services\AmoCRMService;
use App\Services\LinkService;
use App\Services\TransactionService;
use Illuminate\Routing\Controller;

class BaseAmoController extends Controller
{
    /**
     * Сервис с методами для контроллеров
     * @var AmoCRMService
     */
    public $service;
    public $linkService;
    public $trnsactionService;
    public $actionService;
    public function __construct(AmoCRMService $service,  LinkService $linkService, TransactionService $trnsactionService, ActionService $actionService)
    {
        $this->service = $service;
        $this->trnsactionService = $trnsactionService;
        $this->linkService = $linkService;
        $this->actionService = $actionService;
    }
}
