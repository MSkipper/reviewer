<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use Symfony\Component\HttpFoundation\Response;

/**
 * @NamePrefix("api_")
 */
class ProductController extends FOSRestController
{
    /**
     * This function get products list from Ceneo and return it as JSON records
     * @Get("/api/products/{name}", name="api_get_products", requirements={"name"=".+"})
     * @param $name
     * @return Response
     */
    public function getProductsAction($name)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://www.ceneo.pl/OpenSearch/SuggestHtmlJqueryJson?&q=". urlencode($name) ."&limit=12");

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        return new Response($server_output, Response::HTTP_OK);
    }

    /**
     * This function get product detail from Ceneo and return it as JSON record
     * @Get("/api/product/{id}", name="api_get_product", requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
    public function getProductAction($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "www.ceneo.pl/". $id);

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        if (preg_match('/ado\.master\((\{.*?\})\)/ms', $server_output, $match)){
            return new Response($match[1], Response::HTTP_OK);
        } else {
            return new Response('Produkt nie istnieje', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * This function get product reviews from Ceneo and return it as JSON record
     * @Get("/api/product/review/{id}", name="api_get_product_review", requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
    public function getProductReviewAction($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "www.ceneo.pl/". $id);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        return new Response($server_output, Response::HTTP_OK);

    }


}