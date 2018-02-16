<?php

namespace Application;

use MartynBiz\Slim3Controller\Controller;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class BaseController extends Controller
{
    public function index(): Response
    {
        return $this->getResponse()->withJson([], 200);
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    protected function getResponse(): Response
    {
        return $this->response;
    }
}