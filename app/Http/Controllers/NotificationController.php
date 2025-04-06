<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('notifications.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return view('notifications.store');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        return view('notifications.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        return view('notifications.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        return view('notifications.update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        return view('notifications.destroy');
    }
}
