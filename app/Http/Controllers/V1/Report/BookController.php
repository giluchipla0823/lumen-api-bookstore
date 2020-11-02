<?php


namespace App\Http\Controllers\V1\Report;

use App\Http\Controllers\ApiController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BookController extends ApiController
{
    /**
     * Gets Book Report.
     *
     * @param Request $request
     */
    public function index(Request $request){
        // attachment
        $disposition = $request->query->get('disposition', 'inline');

        $baseUrl = "http://143.110.136.51:8080/jasperserver/";
        $url = $baseUrl . "rest_v2/reports/reports/Test/bookstore.pdf";

        $username = 'jasperadmin';
        $password = 'jasperadmin';

        $client = new Client(['http_errors' => false]);

        $response = $client->request('GET', $url, [
            'query' => array(
                'j_username' => $username,
                'j_password' => $password,
                'NOMBRE' => 'LUIGGI'
            )
        ]);

        header("Content-type: application/pdf");
        header("Content-Disposition: {$disposition};filename=filename.pdf");

        echo $response->getBody();
        exit();
    }
}