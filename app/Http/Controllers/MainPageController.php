<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
      'attributes' => auth()->user()->playerAttributes->mapWithKeys(function ($attribute) {
        return [$attribute->playerAttributesDefinition->name => $attribute->value];
      })
    ]);
  }
}