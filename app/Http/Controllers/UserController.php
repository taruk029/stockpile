<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\User;
use App\Company;
use App\Location;
use App\User_profile;
use Validator;
use Auth;
use DB;
use App\Helpers\Helper;



class UserController extends Controller

{

   /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('auth');

    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Contracts\Support\Renderable

     */

    public function index()

    {

        $loggedin_user = Helper::get_logged_in_user();

		$users = User::select("users.name as user_name", 

			"users.id as user_id", 

			"users.phone", 

			"users.email", 

			"users.phone",  

            "users.is_active", 

			"user_profiles.user_type")

		->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")

        ->where("users.company_id", $loggedin_user)

        ->where("users.role", 3)

		->get();

        return view('users.index', ['users' => $users]);

    }



    public function add()

    {

        $loggedin_user = Helper::get_logged_in_user();

        return view('users.add', ['loggedin_user' => $loggedin_user]);

    }



    public function insert(Request $request)

    {

        $request->validate([

            'first_name' => 'required|string',

            'last_name' => 'required|string',

            'password' => 'required|string',

            'user_type' => 'required',

            'email' => 'required|string|email|unique:users',

            'phone' => 'required|numeric|unique:users',

            'company_id' => 'required|numeric'

        ]);



        $user = new User;

        $user->name = $request->first_name." ".$request->last_name;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->role = 3;

        $user->password = bcrypt($request->password);

        $user->company_id = $request->company_id;

        if($user->save())

        {



            $user_profile = new User_profile;

            $user_profile->user_id = $user->id;

            $user_profile->user_type = $request->user_type;

            $user_profile->save();



            flash('User has been added successfully.')->success();

            return redirect('my_users'); 

            

        }

        else

        {

            flash('Please fill the form correctly.')->error();

            return redirect()->back();

        }

    }



    public function edit($id)

    {

        if($id)

        {

            $user = User::select("users.name as user_name", 

            "users.id as user_id", 

            "users.*", 

            "user_profiles.user_type")

            ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")            

            ->where("users.id", $id)

            ->where("users.role", 3)

            ->first();



            return view('users.edit', ['user' => $user]);

        }

    }





    public function update(Request $request)

    {

        $request->validate([

            'first_name' => 'required|string',

            'last_name' => 'required|string'

        ]);



        $user = User::find($request->user_id);

        $user->name = $request->first_name." ".$request->last_name;

        if(!empty($request->password))

            $user->password = bcrypt($request->password);

        if($user->save())

        {

            $user_profile = User_profile::where('user_id', $request->user_id)->first();

            $user_profile->user_type = $request->user_type;

            $user_profile->save();



            flash('User has been updated successfully.')->success();

            return redirect('my_users');             

        }

        else

        {

            flash('Please fill the form correctly.')->error();

            return redirect()->back();

        }

    }


public function change_status($id, $status)
    {
        $user = User::find($id);
        $user->is_active = $status;
        $user->save();

        if($status==0)
            flash('User has been deactivated successfully.')->success();
        else
            flash('User has been activated successfully.')->success();
            
        return redirect('my_users'); 
    }


}

