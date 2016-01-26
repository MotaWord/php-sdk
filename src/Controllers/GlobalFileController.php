<?php
namespace MotaWord\Controllers;

use MotaWord\APIException;
use MotaWord\APIHelper;
use MotaWord\Configuration;
use Unirest\File;
use Unirest\Unirest;

/**
 * Class GlobalFileController
 *
 * Manage your corporate account's global style guide and glossaries.
 *
 * @package MotaWord\Controllers
 */
class GlobalFileController
{

    /**
     * Create or update your corporate account's global style guide
     *
     * @param $styleguide
     *
     * @return mixed
     *
     * @throws APIException
     * @throws \Exception
     */
    public function updateStyleGuide($styleguide)
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;

        //prepare query string for API call
        $queryBuilder = $queryBuilder . '/styleguide';

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array(
            'user-agent' => 'APIMATIC 2.0',
            'Accept' => 'application/json',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthAccessToken)
        );

        //prepare parameters
        $parameters = array(
            "styleguide" => File::add($styleguide)
        );

        //prepare API request
        $request = Unirest::post($queryUrl, $headers, $parameters);

        //and invoke the API call request to fetch the response
        $response = Unirest::getResponse($request);

        //Error handling using HTTP status codes
        if ($response->code == 400) {
            throw new APIException('MissingCorporateAccount | FileTooSmall | FileTooLarge | NoFileUploaded', 400, $response->body);
        } else if ($response->code == 405) {
            throw new APIException('UnsupportedStyleGuideFormat', 405, $response->body);
        } else if (($response->code < 200) || ($response->code > 206)) { //[200,206] = HTTP OK
            throw new APIException("HTTP Response Not OK", $response->code, $response->body);
        }

        return $response->body;
    }

    /**
     * Download your corporate account's global style guide
     *
     * @return string       File content
     *
     * @throws APIException
     * @throws \Exception
     */
    public function downloadStyleGuide()
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;

        //prepare query string for API call
        $queryBuilder = $queryBuilder . '/styleguide';

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array(
            'user-agent' => 'APIMATIC 2.0',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthAccessToken)
        );

        //prepare API request
        $request = Unirest::get($queryUrl, $headers);

        //and invoke the API call request to fetch the response
        $response = Unirest::getResponse($request);

        //Error handling using HTTP status codes
        if ($response->code == 404) {
            throw new APIException('StyleGuideNotFound', 404, $response->body);
        } else if (($response->code < 200) || ($response->code > 206)) { //[200,206] = HTTP OK
            throw new APIException("HTTP Response Not OK", $response->code, $response->body);
        }

        return $response->body;
    }

    /**
     * Create or update your corporate account's global glossary
     * @param $glossary
     * @return mixed
     * @throws APIException
     * @throws \Exception
     */
    public function updateGlossary($glossary)
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;

        //prepare query string for API call
        $queryBuilder = $queryBuilder . '/glossary';

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array(
            'user-agent' => 'APIMATIC 2.0',
            'Accept' => 'application/json',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthAccessToken)
        );

        //prepare parameters
        $parameters = array(
            "glossary" => File::add($glossary)
        );

        //prepare API request
        $request = Unirest::post($queryUrl, $headers, $parameters);

        //and invoke the API call request to fetch the response
        $response = Unirest::getResponse($request);

        //Error handling using HTTP status codes
        if ($response->code == 400) {
            throw new APIException('MissingCorporateAccount | FileTooSmall | FileTooLarge | NoFileUploaded', 400, $response->body);
        } else if ($response->code == 405) {
            throw new APIException('UnsupportedGlossaryFormat', 405, $response->body);
        } else if (($response->code < 200) || ($response->code > 206)) { //[200,206] = HTTP OK
            throw new APIException("HTTP Response Not OK", $response->code, $response->body);
        }

        return $response->body;
    }

    /**
     * Download your corporate account's global glossary
     *
     * @return string       File content
     *
     * @throws APIException
     * @throws \Exception
     */
    public function downloadGlossary()
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;

        //prepare query string for API call
        $queryBuilder = $queryBuilder . '/glossary';

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array(
            'user-agent' => 'APIMATIC 2.0',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthAccessToken)
        );

        //prepare API request
        $request = Unirest::get($queryUrl, $headers);

        //and invoke the API call request to fetch the response
        $response = Unirest::getResponse($request);

        //Error handling using HTTP status codes
        if ($response->code == 404) {
            throw new APIException('GlossaryNotFound', 404, $response->body);
        } else if (($response->code < 200) || ($response->code > 206)) { //[200,206] = HTTP OK
            throw new APIException("HTTP Response Not OK", $response->code, $response->body);
        }

        return $response->body;
    }

}