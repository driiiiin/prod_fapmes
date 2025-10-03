<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->userlevel != -1) {
            abort(403, 'Unauthorized');
        }

        $query = Audit::query();

        // Optional filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->orderByDesc('id')->paginate(30);

        return view('app.audit.index', compact('audits'));
    }
}
