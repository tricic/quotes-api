<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'random');
    }

    public function index()
    {
        $quotes = Quote::all();

        return response([
            'message' => 'Success.',
            'data' => QuoteResource::collection($quotes)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'quote' => 'required',
            'author' => 'max:255',
            'category' => 'max:64'
        ]);

        $quote = Quote::create($request->all() + ['user_id' => $request->user()->id]);

        return response([
            'message' => 'Success.',
            'data' => new QuoteResource($quote)
        ]);
    }

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

    public function update(Request $request, Quote $quote)
    {
        $request->validate([
            'quote' => 'required',
            'author' => 'max:255',
            'category' => 'max:64'
        ]);

        $quote->update($request->all());

        return response([
            'message' => 'Success.',
            'data' => new QuoteResource($quote)
        ]);
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return response([
            'message' => 'Success.'
        ]);
    }
}
