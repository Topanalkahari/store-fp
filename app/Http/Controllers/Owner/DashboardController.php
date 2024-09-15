<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $filter = $request->input('filter');

        // Handle quick filter options
        if ($filter) {
            switch ($filter) {
                case 'today':
                    $startDate = $endDate = Carbon::today()->format('Y-m-d');
                    break;
                case 'this_week':
                    $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'this_month':
                    $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                    break;
            }
        }

        // Initialize queries
        $userQuery = User::query();
        $transactionQuery = Transaction::query();

        // Apply date filters if set
        if ($startDate && $endDate) {
            $userQuery->whereBetween('created_at', [$startDate, $endDate]);
            $transactionQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Calculate metrics
        $customer = $userQuery->count();
        $revenue = $transactionQuery->sum('total_price');
        $transaction = $transactionQuery->count();

        return view('pages.owner.dashboard', [
            'customer' => $customer,
            'revenue' => $revenue,
            'transaction' => $transaction,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}