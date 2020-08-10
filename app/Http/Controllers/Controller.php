<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Test;

class Controller extends BaseController
{
    public function test(Request $request) {
        $domain = $request->input('domain');

        $db_domain = Test::where('domain', $domain)->get();

        if (empty($db_domain)) {
            DB::beginTransaction();

            try {
                $db_domain = new Test();
                $db_domain->domain = $domain;
                $db_domain->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }
    }
}
