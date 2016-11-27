<?php namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SourcesRepository {


    public function getAllSources(){
        $q = \DB::select("SELECT * FROM ta_sources WHERE hidden = 0");
        return $q;

    }

}