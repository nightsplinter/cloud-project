<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Exception;

/**
 * This controller is responsible for handling requests from analysts.
 *
 */
class AnalystController extends Controller
{
    /**
     * Show the analysis dashboard.
     * @return View
     */
    public function index(): View
    {
        return view('analysis');
    }

    /**
     * Run the command to fetch and store data from the API.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runCommand()
    {
        try {
            $output = Artisan::call('app:daily-api-call');
            $outputMessage = Artisan::output();

            if (0 === $output) { // 0 means success
                return back()
                    ->with(
                        'status',
                        'Command erfolgreich ausgeführt! ' . $outputMessage
                    );
            } else {
                return back()
                    ->with(
                        'error',
                        'Command fehlgeschlagen. Output: ' . $outputMessage
                    );
            }
        } catch (Exception $e) {
            return back()
                ->with(
                    'error',
                    'Fehler beim Ausführen des Commands: ' . $e->getMessage()
                );
        }
    }
}
