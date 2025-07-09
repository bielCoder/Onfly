<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Classes\Utilities\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
// use App\Notifications\NotifyGmail;
use App\Mail\NotifyGmail;
use App\Models\User;

class OrderController extends Controller
{



    private $response;
    private $order;
    private $user;

    public function __construct(Response $response, Order $order, User $user)
    {
        $this -> response = $response;
        $this -> order = $order;
        $this -> user = $user;
    }

    /**
     * Display a listing of the resource.
     */
        public function index(Request $request)
        {
            try {
                $query = $this->order->query();

                // Filtro por status
                if ($request->has('status')) {
                    $query->where('status', $request->status);
                }

                // Filtro por período (data de partida ou retorno)
                if ($request->has('start_date') && $request->has('end_date')) {
                    $query->where(function($q) use ($request) {
                        $q->whereBetween('departure_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('return_date', [$request->start_date, $request->end_date]);
                    });
                }

                // Filtro por destino
                if ($request->has('city')) {
                    $query->where('city', 'like', '%' . $request->destination . '%');
                }

                $travellings = $query->paginate($request->per_page ?? 10);

                if ($travellings->isEmpty()) {
                    return $this->response->error(
                        "order",
                        $request->header('Content-Type'),
                        strtoupper($request->method()),
                        "Nenhuma viagem encontrada",
                        404
                    );
                }

                return $this->response->format(
                    "travellings",
                    $request->header('Content-Type'),
                    strtoupper($request->method()),
                    $travellings,
                    null,
                    null,
                    200
                );

            } catch (\Exception $e) {
                return $this->response->error(
                    "order",
                    $request->header('Content-Type'),
                    strtoupper($request->method()),
                    $e->getMessage(),
                    500
                );
            }
        }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this -> order -> create($request -> all());
             return $this -> response -> format("order",$request->header('Content-Type'),strtoupper($request->method()),$this -> order,null,"Pedido de viagem criado com sucesso.",200);
        } catch(\Exception $e)
        {
            return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        } catch(\PDOException $e)
        {
            return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        }

    }

    /**
     * Display the specified resource.
     */
     public function show(Request $request,int $id)
    {
         try {

            $order = $this -> order -> find($id);
               if(!$order)
                {
                    return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),"Nenhuma viagem encontrada",404);
                }
                    return $this -> response -> format("order",$request->header('Content-Type'),strtoupper($request->method()),$order,null,null,200);

        } catch(\Exception $e)
        {
            return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        } catch(\PDOException $e)
        {
            return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function changeStatus(Request $request)
    {

        try {

            $user = $this -> user -> where('id',$request -> user_id) -> first();

            if(!$user)
            {
                 return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),"E-mail de usuário não encontrado",404);
            }

           Mail::to($user -> email)->send(new NotifyGmail($user -> email, $request -> status, $user -> name));

            $this -> order -> where('user_id',$request -> user_id) -> where('travelling_id',$request -> travelling_id) -> update([
                "status" => $request -> status
            ]);
              return $this -> response -> format("order",$request->header('Content-Type'),strtoupper($request->method()),null,null,"O status da viagem foi alterado para ".$request -> status,200);
        } catch(\Exception $e)
        {
              return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        } catch(\PDOException $e)
        {
              return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        }


    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $order =  $this -> order -> where('user_id',$request -> user_id) -> where('travelling_id',$request -> travelling_id) -> first();
            if($order !== 'aprovado')
            {
                  $this -> order -> where('id',$id) -> update([
                    "status" => 'cancelado'
                ]);
                 return $this -> response -> format("order",$request->header('Content-Type'),strtoupper($request->method()),null,null,"O pedido de viagem foi cancelado",200);
            }
                return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),"O pedido não pode ser cancelado pois o mesmo já foi aprovado.",400);


        } catch(\Exception $e)
        {
              return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        } catch(\PDOException $e)
        {
              return $this -> response -> error("order",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        }

    }
}
