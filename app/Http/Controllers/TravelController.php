<?php

namespace App\Http\Controllers;

use App\Classes\Utilities\Response;
use App\Models\Travel;
use Illuminate\Http\Request;

class TravelController extends Controller
{

    private $response;
    private $travellings;

    public function __construct(Response $response, Travel $travellings)
    {
        $this -> response = $response;
        $this -> travellings = $travellings;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // return travellings
            $travellings = $this -> travellings -> paginate($request -> per_page ?? 10);
            if($travellings -> total() === 0)
            {
                return $this -> response -> error("travellings",$request->header('Content-Type'),strtoupper($request->method()),"Nenhuma viagem encontrada",404);
            }
            return $this -> response -> format("travellings",$request->header('Content-Type'),strtoupper($request->method()),$travellings,null,null,200);



        } catch(\Exception $e)
        {
            return $this -> response -> error("travellings",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        } catch(\PDOException $e)
        {
            return $this -> response -> error("travellings",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        }


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,int $id)
    {
         try {

            $travelling = $this -> travellings -> find($id);
               if(!$travelling)
                {
                    return $this -> response -> error("travellings",$request->header('Content-Type'),strtoupper($request->method()),"Nenhuma viagem encontrada",404);
                }
                    return $this -> response -> format("travellings",$request->header('Content-Type'),strtoupper($request->method()),$travelling,null,null,200);

        } catch(\Exception $e)
        {
            return $this -> response -> error("travellings",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        } catch(\PDOException $e)
        {
            return $this -> response -> error("travellings",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
