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

            // Create a trigger file that the host system can monitor
            $triggerFile = '/tmp/shutdown-requested';

            file_put_contents($triggerFile, date('Y-m-d H:i:s') . ' - Shutdown requested by web interface');

            return response()->json([
                'ok' => true,
                'message' => 'Shutdown request sent. The system will shutdown shortly.',
            ]);
        } catch (\Exception $e) {
            Log::error('Exception during shutdown request', ['error' => $e->getMessage()]);
            return response()->json([
                'ok' => false,
                'message' => 'An error occurred while requesting shutdown',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function restart(Request $request)
    {
        try {
            Log::info('System restart requested by user');

            // Create a trigger file that the host system can monitor
            $triggerFile = '/tmp/restart-requested';

            file_put_contents($triggerFile, date('Y-m-d H:i:s') . ' - Restart requested by web interface');

            return response()->json([
                'ok' => true,
                'message' => 'Restart request sent. The system will restart shortly.',
            ]);
        } catch (\Exception $e) {
            Log::error('Exception during restart request', ['error' => $e->getMessage()]);
            return response()->json([
                'ok' => false,
                'message' => 'An error occurred while requesting restart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
