<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'random');
    }

    public function index(Request $request): Response
    {
        $quotes = empty($request->category) ? Quote::all()
            : Quote::where('category', $request->category)->get();

        return response([
            'message' => 'Success.',
            'data' => QuoteResource::collection($quotes)
        ]);
    }

    public function store(Request $request): Response
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

    public function show(Quote $quote): Response
    {
        return response([
            'message' => 'Success.',
            'data' => new QuoteResource($quote)
        ]);
    }

    public function random(Request $request): Response
    {
        $quote = empty($request->category) ? Quote::inRandomOrder()->firstOrFail()
            : Quote::where('category', $request->category)->inRandomOrder()->firstOrFail();

        return response([
            'message' => 'Success.',
            'data' => new QuoteResource($quote)
        ]);
    }

    public function update(Request $request, Quote $quote): Response
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

    public function destroy(Quote $quote): Response
    {
        $quote->delete();

        return response([
            'message' => 'Success.'
        ]);
    }
}
