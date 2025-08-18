<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SystemController extends Controller
{
    public function shutdown(Request $request)
    {
        try {
            Log::info('System shutdown requested by user');

            // Execute shutdown command
            exec('sudo shutdown -h now 2>&1', $output, $returnCode);

            if ($returnCode === 0) {
                return response()->json([
                    'ok' => true,
                    'message' => 'System shutdown initiated successfully',
                ]);
            } else {
                Log::error('Shutdown command failed', ['output' => $output, 'return_code' => $returnCode]);
                return response()->json([
                    'ok' => false,
                    'message' => 'Failed to initiate shutdown',
                    'error' => implode("\n", $output),
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception during shutdown', ['error' => $e->getMessage()]);
            return response()->json([
                'ok' => false,
                'message' => 'An error occurred during shutdown',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function restart(Request $request)
    {
        try {
            Log::info('System restart requested by user');

            // Execute restart command
            exec('sudo reboot 2>&1', $output, $returnCode);

            if ($returnCode === 0) {
                return response()->json([
                    'ok' => true,
                    'message' => 'System restart initiated successfully',
                ]);
            } else {
                Log::error('Restart command failed', ['output' => $output, 'return_code' => $returnCode]);
                return response()->json([
                    'ok' => false,
                    'message' => 'Failed to initiate restart',
                    'error' => implode("\n", $output),
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception during restart', ['error' => $e->getMessage()]);
            return response()->json([
                'ok' => false,
                'message' => 'An error occurred during restart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
