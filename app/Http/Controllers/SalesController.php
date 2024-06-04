<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Jobs\SalesCsvJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;

class SalesController extends Controller
{

    public function index()
    {
        return view('upload-file');
    }
    public function upload()
    {
        if (request()->has('mycsv')) {
            $data = file(request()->mycsv);


            // Creating Chunk
            $chunks = array_chunk($data, 1000);
            // Convert Data into Csv file

            $header = [];

            $batch = Bus::batch([])->dispatch();

            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);
                if ($key === 0) {
                    $header = $data[0];
                    unset($data[0]);
                }

                $batch->add(new SalesCsvJob($data, $header));
            }

            return $batch;
        } else{
            return "Upload Your File.";
        }
    }

    public function batch()
    {
        $batchId = request('id');
        return Bus::findBatch($batchId);
    }
    public function progressBatch()
    {
        $batches = DB::table('job_batches')->where('pending_jobs', '>', 0)->get();
        if (count($batches) > 0) {
            return Bus::findBatch($batches[0]->id);
        }
        return [];
    }


}
