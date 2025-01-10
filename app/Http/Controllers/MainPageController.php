<?php

namespace App\Http\Controllers;

class MainPageController extends Controller
{
    /**
     * Return the main page data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'user' => auth()->user()->only(['id', 'nickname', 'email']),
            'attributes' => auth()->user()->userAttributes->mapWithKeys(function ($attribute) {
                return [$attribute->userAttributeDefinition->name => $attribute->value];
            }),
        ]);
    }
}
