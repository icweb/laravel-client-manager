<?php

namespace App\Http\Controllers;

use App\Http\Requests\ViewsAdminPage;
use App\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ViewsAdminPage $request
     * @return \Illuminate\Http\Response
     */
    public function index(ViewsAdminPage $request)
    {
        return view('admin.index');
    }
}
