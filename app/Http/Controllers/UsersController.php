<?php

namespace App\Http\Controllers;

use App\Repository\Business;
use App\Repository\Customers;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    private $users;
    private $business;

    function __construct()
    {
        $this->users = new User();
        $this->business = new Business();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()) return view('admin.pages.admin-login');
        $currentUser = Auth::user();
        $query = $request->input('search');
        $sidebarUser = 'active';
        if ($query == null) {
            $users = User::paginate(5);
            return view('admin.pages.users.list', compact('users', 'currentUser', 'sidebarUser'));
        } else {
            $users = User::where('name', 'like', "%$query%")->orWhere('email', 'like', "%$query%")->paginate(5);
            return view('admin.pages.users.list', compact('users', 'query', 'currentUser', 'sidebarUser'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, $this->users->rule());
        $data['password'] = bcrypt($data['password']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            // Make by Toan
            $data['role'] = 1;
            $remember_token = generateRandomToken();
            // Make by Toan
            $this->users->create($data);
            return redirect()->route('users.index')->with('success', 'Create new user successfully');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        if (!Auth::user()) return view('admin.pages.admin-login');
        $current_user_id = Auth::user()->id;
        $user = $this->users->findOrFail($current_user_id);
        return view('admin.pages.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $path = 'uploads/img_profile';
        $rule = [
            'name' => 'max:191',
            'email' => 'email',
            'phone' => 'max:30',
            'address' => 'max:191',
            'newpassword' => 'min:6|max:191',
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->has('change_password')) {
            $new_password = bcrypt($request->input('newpassword'));
            if (Hash::check($request->input('password'), Auth::user()->getAuthPassword())) {
                $data['password'] = $new_password;
            } else {
                return redirect()->back()->with('err_password', 'Incorrect password please reenter password');
            }
        } else {
            unset($data['password']);
            unset($rule['newpassword']);
        }
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $data['img'] = $this->business->saveImg($img, $path);
            if ($user->img != null && file_exists($path . '/' . $user->img)) {
                unlink($path . '/' . $user->img);
            }
        }
        /*    $validator = Validator::make($data,$rule);
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{*/
        $user->update($data);
        return redirect()->route('users.profile')->with('success', "Update profile $user->name succesfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    // Modified by Toan
    // public function destroy(Request $request)
    // {
    //     $id = $request->input('user_id');
    //     if ($user_id = $this->users->findOrFail($id)) {
    //         if (($user_id->img != null) && (file_exists("upload/img_product/$user_id->img"))) {
    //             unlink("upload/img_product/$user_id->img");
    //         }
    //         $user_id->delete();
    //         return redirect()->route('users.index')->with('success', "Deleted $user_id->name");
    //     } else {
    //         return redirect()->route('users.index')->with('fail', 'User ko tồn tại');
    //     }
    // }
    // Modified by Toan

    public function getCustomer(Request $request)
    {
        $query = $request->input('search');
        if ($query == null) {
            $customers = Customers::latest()->paginate(5);
            return view('admin.pages.users.customerList', compact('customers'));
        } else {
            $customers = Customers::where('username', 'like', "%$query%")->orWhere('email', 'like', "%$query%")->paginate(5);
            return view('admin.pages.users.customerList', compact('customers', 'query'));
        }
    }

    public function customerDetail($id)
    {
        $customer_id = Customers::find($id);
        echo "<p>Name: $customer_id->username </p>";
        echo "<p>Address: $customer_id->address </p>";
        echo "<p>Email: $customer_id->email</p>";
        echo "<p>Phone Number: $customer_id->phone</p>";
    }


    // Các hàm bổ sung
    // Modified by Toan
    // Write by Phuong, Manh
    public function mobileLogin(Request $request){
        try {
            $data = $request->all();
            $email = $data['email'];
            $password = $data['password'];
            $time = $data['time'];
            $muser=User::where('email', $email)->first();
            if(is_object($muser)){
                $h = md5($muser->password . $time);
                if($h === $password){
                    $remember_token = $this->generateRandomToken();
                    $muser->remember_token = $remember_token;
                    $muser->save();
                    $success=true;
                    $message = "success";
                    return response()->json(compact('success', 'remember_token', 'message'));
                }
                else{
                    $success = false;
                    $message = "Email or password is wrong!";
                    return response()->json(compact('success', 'message'));
                }
            }else{
                $success=false;
                $message = "Email or password is wrong!";
                return response()->json(compact('success', 'message'));
            }
        } catch ( Exception $e){
            $success = false;
            $message = $e->getMessage();
            return response()->json(compact('success', 'message'));
        }
    }

    public function mobileRegister(Request $request){
        try{
            $data = $request->all();
            $email = $data['email'];
            $password = $data['password'];
            $muser=User::where('email', $email)->first();
            if(is_object($muser)){
                $message = "User has already existed!";
                $success = false;
                return response()->json(compact('success' , 'message'));
            }else{
                // Make by Toan
                $success = true;
                $message = "success";
                $data['role'] = 3;
                $data['remember_token'] = $this->generateRandomToken();
                if(!$this->users->create($data)){
                    $success = false; 
                    $message = "Database Error!";
                }
                return response()->json(compact('success','message'));
                // Make by Toan
            }
        }catch(Exception $e){
                $message = $e->getMessage();
                $success = false;
                return responste()->json(compact('success' , 'message'));
        }
    }

    public function mobile_profile(Request $request){
        $remember_token = $request->header('remember_token');
        if(!isset($remember_token)){
            $success=false;
            $message="Something went wrong";
            return response()->json(compact('success', 'message'));
        }
        else {
            $success=true;
            $message = 'success';
            $mobile_user=User::select('email','name','phone','address','avatar','vehicle')->where('remember_token', $remember_token)->first();
            if(is_object($mobile_user)) return response()->json(compact('success', 'message','mobile_user'));
            else{
                $success=false;
                $message="Token not found!";
                return response()->json(compact('success', 'message'));
            }
        }
    }

    public function mobile_profile_edit(Request $request){
        $data = $request->all();
        if($request->header('remember_token') === null){
            $success = false;
            $message = "Don't have token";
        }
        else {
            $mobile_user=User::where('remember_token', $request->header('remember_token'))->first();
            if(!is_object($mobile_user)){
                $success = false;
                $message = "User not found";
            }
            else{
                if(isset($data['name'])) $mobile_user->name = $data['name'];
                if(isset($data['phone'])) $mobile_user->phone = $data['phone'];
                if(isset($data['address'])) $mobile_user->address = $data['address'];
                if(isset($data['vehicle'])) $mobile_user->vehicle = $data['vehicle'];
                $mobile_user->save();
                $success = true;
                $message = "success";
            }
        }
        return response()->json(compact('success', 'message'));
    }

    public function change_password(Request $request){
        $data = $request->all();
        if($request->header('remember_token') === null || !isset($data['old_password']) || !isset($data['new_password'])){
            $success = false;
            $message = "Lack of variable";
        }
        else{
            $mobile_user=User::where('remember_token', $request->header('remember_token'))->where('password', $data['old_password'])->first();
            if(!is_object($mobile_user)){
                $success = false;
                $message = "Wrong information";
            }
            else{
                $mobile_user->password = $data['new_password'];
                $mobile_user->save();
                $success = true;
                $message = "success";
            }
        }
        return response()->json(compact('success', 'message'));
    }

    public function avatar(Request $request){
        $user=User::where('remember_token',$request->header('remember_token'))->first();
        if(isset($user)){
            if($request->hasFile('image')){
                $fileNameWithExtension = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $fileName.'_'.time().'.'.$extension;
                $path=$request->file('image')->storeAs('public/avatars',$fileNameToStore);
            } 
            if(isset($fileNameToStore) && $fileNameToStore != "") {
                $user->avatar=$fileNameToStore;
                $success = true;
                $message = "success";
                $user->save();
            }
            else{
                $success = false;
                $message = "Don't has file image in post";
            }
        }
        return response()->json(compact('success','message'));
    }

    // Hàm chức năng
    // Make by Toan
    public function generateRandomToken($length = 60) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    
}
