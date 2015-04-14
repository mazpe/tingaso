<?php

class ReportsController extends \BaseController {
    public function getReport()
    {
        return View::make('reports.index');
    }

}
