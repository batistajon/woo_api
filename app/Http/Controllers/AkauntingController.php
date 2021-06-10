<?php

namespace App\Http\Controllers;

use App\Models\Akaunting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AkauntingController extends Controller
{
    private $akaunting;
    private $auth;

    public function __construct(Akaunting $akaunting)
    {
        $this->akaunting = $akaunting;
        $this->auth = Http::withBasicAuth(env('AKAUNTING_USER'), env('AKAUNTING_PASS'));
    }

    public function companies()
    {
        $response = $this->auth->get(env('URL_API_AKAUNTING').'/companies');

        return var_dump($response);
    }

    public function ownerCompany($id)
    {
        $response = $this->auth->get(env('URL_API_AKAUNTING')."/companies/$id/owner");

        return $response->json();
    }
}
