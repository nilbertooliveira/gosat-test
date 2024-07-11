<?php

namespace App\Domains\Interfaces\Services;

use App\Application\Services\ResponseService;
use Illuminate\Http\Request;

interface IGraphicalReportService
{
    public function generate(Request $request):ResponseService;
}
