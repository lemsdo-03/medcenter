<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::whereIn('role', ['admin', 'doctor', 'receptionist'])->get();

        $stats = [
            'total' => $staff->count(),
            'admins' => $staff->where('role', 'admin')->count(),
            'doctors' => $staff->where('role', 'doctor')->count(),
            'receptionists' => $staff->where('role', 'receptionist')->count(),
        ];

        return view('admin.staff.index', compact('staff', 'stats'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules());

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
    }

    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate($this->rules($staff));

        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->role = $request->role;

        if ($request->password) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        if ($staff->role === 'doctor') {
            $appointmentCount = \App\Models\Appointment::where('doctor_id', $staff->id)
                ->where('status', '!=', 'cancelled')
                ->count();

            if ($appointmentCount > 0) {
                return back()->with('error', "Cannot delete staff member. They have {$appointmentCount} active appointment(s). Please reassign or cancel appointments first.");
            }
        }

        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }

    private function rules(?User $staff = null): array
    {
        $uniqueEmail = 'unique:users,email';
        if ($staff) {
            $uniqueEmail .= ',' . $staff->id;
        }

        // Admins should NOT be able to create new admins.
        // Existing admin user can only stay admin (role cannot be changed to something else here).
        $roleRule = $staff && $staff->role === 'admin'
            ? 'required|in:admin'
            : 'required|in:doctor,receptionist';

        return [
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|{$uniqueEmail}",
            'password' => $staff ? 'nullable|string|min:3|confirmed' : 'required|string|min:3|confirmed',
            'role' => $roleRule,
        ];
    }
}
