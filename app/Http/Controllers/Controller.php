<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Test;
use Carbon\Carbon;

class Controller extends BaseController
{
    public function test(Request $request) {
        $domain = $request->input('domain');

        $db_domain = Test::where('domain', $domain)->get()->toArray();

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
        } else {
            DB::beginTransaction();

            try {
                $update = Test::find($db_domain['id']);
                $update->updated_at = Carbon::now()->toDateTimeString();
                $update->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }
    }
}
