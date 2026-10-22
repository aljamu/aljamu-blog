<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\HomeModel;
use App\Utils\Config;
use flight\Engine;

/**
 * Welcome page controller — canonical controller example for the skeleton.
 *
 * No Flight:: facade. No $_ENV. Inject what you need.
 */
class HomeController
{
    public $name = "home";

    private Engine $app;

    private Config $config;

    private object $content;

    private HomeModel $homeModel;


    /**
     * @param Engine<object> $app
     */
    public function __construct(Engine $app, Config $config)
    {
        $this->app = $app;
        $this->config = $config;
        $this->homeModel = new HomeModel();
        // $this->content = $this->homeModel->getContent($this->name);
    }

    public function index(): void
    {
        // $featureContent = json_encode($this->content->feature);
        $this->app->render('home', [
            'message' => 'You are gonna do great things!',
            'env' => $this->config->env(),
            'debug' => $this->config->isDebug(),
        ]);
    }
}
