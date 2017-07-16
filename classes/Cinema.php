<?php
defined('APP') OR exit('No direct script access allowed');

class Cinema extends \BaseController
{
    public function getList($request, $response, $args)
    {
        parse_str($request->getUri()->getQuery(), $params);
        $args = array_merge($params, $args);

        // Reconcile arguments
        $args['page'] = ( ! empty($args['page']) ? (int) $args['page'] : 1);
        $args['type'] = ( ! empty($args['type']) ? $args['type'] : 'all');

        require 'lib/web/' . $this->ci['sources'] . '/Cinema.php';
        $source = new \Web\Cinema($args);

        $result = $source->getList();

        // Set Response
        $response = $response->withStatus($result['code'])->withJson($result);

        return $response;
    }

    public function getDetail($request, $response, $args)
    {
        parse_str($request->getUri()->getQuery(), $params);
        $args = array_merge($params, $args);

        require 'lib/web/' . $this->ci['sources'] . '/Cinema.php';
        $source = new \Web\Cinema($args);

        $result = $source->getDetail();

        // Set Response
        $response = $response->withStatus($result['code'])->withJson($result);

        return $response;
    }
}
