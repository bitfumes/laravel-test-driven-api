<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Http\Resources\LabelResource;
use App\Models\Label;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabelController extends Controller
{
    public function index()
    {
        $labels = auth()->user()->labels;
        return LabelResource::collection($labels);
    }

    public function store(LabelRequest $request)
    {
        $label =  auth()->user()
            ->labels()
            ->create($request->validated());
        return new LabelResource($label);
    }

    public function destroy(Label $label)
    {
        $label->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(Label $label, LabelRequest $request)
    {
        $label->update($request->validated());
        return new LabelResource($label);
    }
}
