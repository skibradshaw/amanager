<?php

namespace App\Http\Controllers;

use App\Lease;
use App\Payment;
use App\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $properties = Property::all();
        foreach ($properties as $p) {
            $p->new_leases = Lease::whereHas('apartment', function ($q) use ($p) {
                $q->where('property_id', $p->id);
            })->where('created_at', '>', Carbon::parse('-30 days'))->orderBy('created_at', 'desc')->take(3)->get();

            $p->recent_payments = Payment::whereHas('lease', function ($q) use ($p) {
                $q->whereHas('apartment', function ($y) use ($p) {
                    $y->where('property_id', $p->id);
                });
            })->where('payment_type', '<>', 'Security Deposit')
            ->orderBy('paid_date', 'desc')
            ->take(3)->get();
            // dd(count($p->new_leases));
        }
        // $newLeases = Lease::with('apartment.property')->orderBy('created_at','desc')->get();
        return view('dashboard', [
            'title' => 'Dashboard',
            'properties' => $properties

            ]);
    }
}
