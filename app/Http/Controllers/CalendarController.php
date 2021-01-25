<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new Calendar;
    }

    public function index(Request $request)
    {
        if( ! $request->ajax()) return abort(404);
        return $this->model
            ->orderBy('name')
            ->paginate(0);
    }

    public function store(Request $request)
    {
        if( ! $request->ajax()) return abort(404);
        $request->validate([
            'name'        => 'required|min:5',
            'description' => 'required|min:10',
            'start'       => 'required|string|min:5|max:255',
            'end'         => 'required|string|min:5|max:255',
            'color'       => 'required',
        ]);
        $calendar = $this->model->create($request->all());
        return $calendar;
    }

    public function update(Calendar $calendar, Request $request)
    {
        if( ! $request->ajax()) return abort(404);
        $request->validate([
            'id'          => 'required|exists:calendars,id',
            'name'        => 'required|min:5',
            'description' => 'required|min:10',
            'start'       => 'required|string|min:5|max:255',
            'end'         => 'required|string|min:5|max:255',
            'color'       => 'required',
        ]);
        $calendar->update($request->all());
        return $calendar;
    }

    public function destroy(Calendar $calendar, Request $request)
    {
        return $calendar->delete();
    }
}

