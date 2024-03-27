<?php

namespace App\Http\Controllers\Api\Courier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pathao;
use App\Models\Steadfast;
use App\Models\Redx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class CourierController extends Controller
{
    public function steadfast(Request $request)
    {
        $data = Steadfast::all()->first();
        return view('backend.CourierApi.Steadfast.index', [
            'datas' => $data
        ]);
    }

    public function  steadfast_create(Request $request)
    {
        $validated = $request->validate([
            'api_url' => 'required|max:255',
            'api_key' => 'required',
            'api_secret' => 'required',
        ]);

        // The blog post is valid.
        $data = Steadfast::all()->first();
        if ($data) {
            $data->update($validated);
        } else {
            Steadfast::create($validated);
        }

        return view('backend.CourierApi.Steadfast.index', [
            $validated,
            'datas' => $data
        ]);
    }

    public function steadfast_order(Request $request)
    {

        $validated = $request->validate([
            'selected_items' => 'required',
        ]);

        foreach ($request->selected_items as $key => $value) {
            $orders = Order::find($value);
            if (!empty($orders->courier_status)) {
                return to_route('all_orders.index')->with('warning', 'Your order already has courier status');
            }

            //steadfast api
            $steadfast = Steadfast::all()->first();
            $address = json_decode($orders->shipping_address);
            //steadfast api
            $response = Http::withHeaders([
                'Api-Key' => $steadfast->api_key,
                'Secret-Key' =>  $steadfast->api_secret,

                'Content-Type' => 'application/json'

            ])->post($steadfast->api_url . '/create_order', [
                'invoice' =>  $orders->code,
                'recipient_name' => $address->name,
                'recipient_address' => $address->address,
                'recipient_phone' => $address->phone,
                'cod_amount' => $orders->grand_total,
                'note' => 'N/A',

            ]);
            $orders->update(['courier_status' => 'steadfast']);
        }
        return to_route('all_orders.index')->with('success', 'Your order has been updated successfully');
    }


    //   pathao
    public function pathao_index(Request $request)
    {
        return view('backend.CourierApi.Pathao.index', [
            'data' => Pathao::all()->first()
        ]);
    }


    public function pathao_create(Request $request)
    {
        $validated = $request->validate([
            'api_url' => 'required|max:255',
            'api_key' => 'required',
            'api_secret' => 'required',
            'api_email' => 'required|email',
            'api_password' => 'required',
        ]);

        // pathao create or update
        $data = Pathao::all()->first();

        if ($data) {
            $token = $this->pathao_token($request);
            $data->update($validated);
        } else {
            $token = $this->pathao_token($request);
            $validated['api_token'] = $token['access_token'];
            $validated['refresh_token'] = $token['refresh_token'];
            // pathao create
            Pathao::create($validated);
        }
        // redirect to page
        return redirect()->back();
    }



    public function pathao_order(Request $request)
    {

        $validated = $request->validate([
            'selected_items' => 'required',
        ]);
        $pathao = Pathao::all()->first();

        $token="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImQxOWY5MzAxMzBlNDc0MjYwODQ4YTAxNjhmNDE0MDFhZmE4MDM2MTI3NDM3MGE3NjEyN2Y4NDU2NjRkYmEyMTdmYjFjYTlkZjkxZjYxZWU2In0.eyJhdWQiOiI0NjA5IiwianRpIjoiZDE5ZjkzMDEzMGU0NzQyNjA4NDhhMDE2OGY0MTQwMWFmYTgwMzYxMjc0MzcwYTc2MTI3Zjg0NTY2NGRiYTIxN2ZiMWNhOWRmOTFmNjFlZTYiLCJpYXQiOjE3MTE0NTg4NzgsIm5iZiI6MTcxMTQ1ODg3OCwiZXhwIjoxNzE5MjM0ODc4LCJzdWIiOiIxODA3MjQiLCJzY29wZXMiOltdfQ.yfOpF5Hfy_KIWkqPHRwBcE4eAVR0U-x94ObFYBP8s--zdX09Uysrq6uzSrkXiMwajz8g1upq8zTdRbSJYehyXLkQampYY_L6Ooxw1GDMBjZ1mxVYoJlhGeEFiYBQhwZ-VjmGgHZPohOVf1jOCYA0iUE57njZ5PB_ZRNa1E8yL1e2aCauSjnJ-UVTj3-2aLnzDY_o6CJ7T3mxT58pXldTOLxbhNJArGyS-xpXX383dBiu4LcWzK85t4rHp8DfXw-h71YOLClul-3OindWLUonWXO_7uDRal2VNLTzN4jTudeSe2_sqNiA13tMwphUa3mP-73qj2GYgPhbOGFVfE1fHPwofKhSJ9L1kdOC3ZghjToPa34IJ7foA-majjcSCK_sw9SMR4ctYazeG3v3yXtfKQLK_hgdvVRW7bhZphBQ46IHpFHNTVMnmzVMds5qI_bOlIJnRhhqpmPY5YW9tFPs27e1Jg8H791TLvxKQqSy8czor_oRRnx_fPSIpvGpH-XkDHU79o306A-KcxOenYGmAwgglM_Ol9GXx7-a_sySl1wjM1yDGfFwqHRVpriGAPBFdZdCNmsFy__t3mTFfF83Q3Xy-sQtiFZWrRVFyRnSzYV0YX3eNJjX2mW1eJYKqUoygMqxqfzpzMn783Mflqpg2HCjbsMAUGsV_Dt8K9b0XO8";
        foreach ($request->selected_items as $key => $value) {
 
            $response = Http::withHeaders([
                'Authorization' => "Bearer"." " . $token,
                'Content-Type' => 'application/json',
            ])
                ->post('https://api-hermes.pathao.com/aladdin/api/v1/orders', [
                    'customer_name' => 'sldfklsds',
                    'customer_phone' => '<customer_phone>',
                    'delivery_area' => '<delivery_area>',
                    'delivery_area_id' => 'fjdksal',
                    'customer_address' => '<customer_address>',
                    'merchant_invoice_id' => '<merchant_invoice_id>',
                    'cash_collection_amount' => '<cash_collection_amount>',
                    'parcel_weight' => '500',
                    'value' => '200',
                ]);

                return  $response->json();

            // Check for any errors
            if ($response->failed()) {
                // Handle the error
                return $response->json();
                // Example: $response->status(), $response->body()
            } else {
                // Process the successful response
                $responseData = $response->json();
                return $responseData;
                // Do something with $responseData
            }
        }

        //return redirect()->back();
    }


    public function pathao_token(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])
                ->post($request->api_url . '/aladdin/api/v1/issue-token', [
                    'client_id' => $request->api_key,
                    'client_secret' => $request->api_secret,
                    'username' => $request->api_email,
                    'password' => $request->api_password,
                    'grant_type' => 'password',
                ]);
            // Check for any errors
            if ($response->failed()) {
                // Handle the error
                return $response->json();
            } else {
                // Process the successful response
                $responseData = $response->json();
                return $responseData;
                // Do something with $responseData
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }









    //redx
    public function redx_index(Request $request)
    {
        return view('backend.CourierApi.Redx.index', [
            'data' => Redx::all()->first()

        ]);
        //echo "redx";
    }
    public function redx_create(Request $request)
    {
        $validated = $request->validate([
            'api_url' => 'required|max:255',
            'token' => 'required',
        ]);

        // The blog post is valid.
        $data = Redx::all()->first();
        if ($data) {
            $data->update($validated);
        } else {
            Redx::create($validated);
        }

        return view('backend.CourierApi.redx.index', [
            $validated,
            'data' => $data
        ]);
    }



    public function redx_order(Request $request)
    {

        $validated = $request->validate([
            'selected_items' => 'required',
        ]);
        foreach ($request->selected_items as $key => $value) {
            $orders = Order::find($value);

            if (!empty($orders->courier_status)) {
                return to_route('all_orders.index')->with('warning', 'Your order already has courier status');
            }


            //steadfast api
            $redx = Redx::all()->first();
            $address = json_decode($orders->shipping_address);
            // redx api
            $response = Http::withHeaders([
                'API-ACCESS-TOKEN' => 'Bearer' . " " . $redx->api_token,
                'Content-Type' => 'application/json'
            ])->Post($redx->api_url, [
                'customer_name' => $address->name,
                'customer_phone' => $address->phone,
                'delivery_area' => $address->city,
                'delivery_area_id' => 'fjdksal',
                'customer_address' => $address->address,
                'merchant_invoice_id' => $orders->code,
                'cash_collection_amount' => $orders->grand_total,
                'parcel_weight' => '500',
                //'instruction' => '',
                'value' => '200',
                //'is_closed_box' => false,
                //'parcel_details_json' => [
                //    [
                //        'name' => 'item1',
                //        'category' => 'category1',
                //        'value' => 120.05
                //    ]
                //]
            ]);


            $orders->update(
                ['courier_status' => 'redx'],
                ['tracking_code' => "123",]
            );
            // Check if request was successful
            if ($response->successful()) {
                $data = $response->json(); // Convert response to JSON
                var_dump($data);
                // Process response data here
            } else {
                // Handle failed request
                $statusCode = $response->status(); // Get the HTTP status code
                $get_error = $response->json();
                var_dump($get_error);
                echo $statusCode;
                // Handle error
            }
        }
    }
}
