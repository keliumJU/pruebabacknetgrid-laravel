<?php

namespace App\Http\Controllers;

    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
    $credentials = $request->only('email', 'password');
    try {
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 400);
        }
    } catch (JWTException $e) {
        return response()->json(['error' => 'could_not_create_token'], 500);
    }
    return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
    try {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
        }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }


    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'nickname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'tipo_identi' => 'required|string|max:255',
            'birthday' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'nickname' => $request->get('nickname'),
            'name' => $request->get('name'),
            'lastname' => $request->get('lastname'),
            'tipo_identi' => $request->get('tipo_identi'),
            'birthday' => $request->get('birthday'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function index(Request $request)
    {   
        $ini = $request->get('ini');
        $end = $request->get('end');

        $users = DB::table('users')
                ->offset($ini)
                ->limit($end)
                ->get();

        //echo $users;

        return response()->json($users,201);
    }

    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch(\Exception $exception){
            dd($exception);
            $errormsg = 'No Customer to delete!' . $exception->getCode();
            return response()->json(['errormsg'=>$errormsg]);
        }

        return response()->json($user,201);
    }


    public function update(Request $request, $id)
    {

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }

        $datosUser=request()->except(['_token','_method']);
        User::where('id','=',$id)->update($datosUser);

        $user_response=User::findOrFail($id);

        return response()->json(compact('user_response'));
    }

    
    public function destroy($id)
    {
        //User::destroy($id);
        //return redirect('empleado');
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
        }

        try {
            $user_del= User::findOrFail($id);
        } catch(\Exception $exception){
            dd($exception);
            $errormsg = 'No Customer to delete!' . $exception->getCode();
            return response()->json(['errormsg'=>$errormsg]);
        }

        $result = $user_del->delete();
        if ($result) {
            $user_response['result'] = true;
            $user_response['message'] = "Customer Successfully Deleted!";
        } else {
            $user_response['result'] = false;
            $user_response['message'] = "Customer was not Deleted, Try Again!";
        }
        return json_encode($user_response, JSON_PRETTY_PRINT);
    }
}