<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; //uses the db called user
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //hash pawword 
//
class StaffController extends Controller
{//shows staff page and stats
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
//shows add staff
    public function create()
    {
        return view('admin.staff.create');
    }
//saves new staff from the form we fill
    public function store(Request $request)
    {
        $request->validate($this->rules());
//input 
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
//redirect to the route
        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
    }
//editing 
    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        
        $request->validate($this->rules($staff));
//update thoes info
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->role = $request->role;
//only update if we choose to
        if ($request->password) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }
//delete 
    public function destroy(User $staff)
    {
        //if the dr have appointments show error message
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
//rules of creation and other stuff
    private function rules(?User $staff = null): array
    {
        $uniqueEmail = 'unique:users,email'; //new user
        if ($staff) {
            $uniqueEmail .= ',' . $staff->id;
        }

        // Admins should NOT be able to create new admins.
        // Existing admin user can only stay admin
        //$ means this is a variable
        $roleRule = $staff && $staff->role === 'admin'
            ? 'required|in:admin' //if yes then it stays admin if no dr or res
            : 'required|in:doctor,receptionist';

        return [
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|{$uniqueEmail}",
            'password' => $staff ? 'nullable|string|min:3|confirmed' : 'required|string|min:3|confirmed',
            'role' => $roleRule,
        ];
    }
}
