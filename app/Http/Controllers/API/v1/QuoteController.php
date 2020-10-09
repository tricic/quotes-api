<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotes = Quote::all();

        return response([
            'message' => 'Success.',
            'data' => QuoteResource::collection($quotes)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'quote' => 'required',
            'author' => 'max:255',
            'category' => 'max:64',
            'user_id' => 'integer'
        ]);

        $quote = Quote::create($request->all());

        return response([
            'message' => 'Success.',
            'data' => new QuoteResource($quote)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        return response([
            'message' => 'Success.',
            'data' => new QuoteResource($quote)
        ]);
    }

    public function random(Request $request)
    {
        $quote = $request->has('category') ?
            Quote::where('category', $request->category)->inRandomOrder()->firstOrFail()
            : Quote::inRandomOrder()->firstOrFail();

        return response([
            'message' => 'Success.',
            'data' => new QuoteResource($quote)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        $request->validate([
            'quote' => 'required',
            'author' => 'max:255',
            'category' => 'max:64',
            'user_id' => 'integer'
        ]);

        $quote->update($request->all());

        return response([
            'message' => 'Success.',
            'data' => new QuoteResource($quote)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();

        return response([
            'message' => 'Success.'
        ]);
    }
}
