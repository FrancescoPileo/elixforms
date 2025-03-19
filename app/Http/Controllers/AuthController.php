<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        //validate the incoming request
        $credentials = $request->only(['username', 'password']);
        $validator = Validator::make($credentials, [
            'username' => 'required|string|size:7',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], Response::HTTP_BAD_REQUEST);
        }

        //check if user already exists
        $user = User::where('username', $credentials['username'])->first();
        if ($user) {
            return response()->json([
                'error' => 'User already exists'
            ], Response::HTTP_CONFLICT);
        }

        //check AD user
        $ad_user = $this->univpm_AD_login($credentials['username'], $credentials['password']);
        if (!$ad_user) {
            return response()->json([
                'error' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        //create User
        $user = User::create([
            'username' => $credentials['username'],
            'fullname' => $ad_user["displayname"],
            'email' => $ad_user["mail"],
        ]);

        return response()->json([
            'message' => 'User created',
        ], Response::HTTP_CREATED);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['username', 'password']);
        $validator = Validator::make($credentials, [
            'username' => 'required|string|size:7',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], Response::HTTP_BAD_REQUEST);
        }

        //check if user exists
        $user = User::where('username', $credentials['username'])->first();
        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        //check AD user
        $ad_user = $this->univpm_AD_login($credentials['username'], $credentials['password']);
        if (!$ad_user) {
            return response()->json([
                'error' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        //generate token
        if (!$token = auth('api')->fromUser($user)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }
    
    /**
     * Logout user.
     *
     * @return void
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }
    
    /**
     * Generate token response.
     *
     * @param  mixed $token
     * @return void
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }

    /**
     * Univpm LDAP ad_user
     *
     * @param  mixed $username
     * @param  mixed $password
     * @return mixed|false $result
     *      $response["stato"] 0=Utente non autenticato | 1=Utente autenticato | 2=Dati mancanti o errati | 
     *                         3=Infrastruttura non disponibile | 4=Errore generico | 5=Logout effettuato
     *      $response["messaggio"]      messaggio login
     *      $response["username"]       matricola
     *      $result["cn"]               matricola
     *      $result["displayname"]      nome completo
     *      $result["title"]            tipo utente
     *      $result["department"]       dipartimento
     *      $result["mail"]             email
     *      $result["dn"]               domain name
     *      $result["givenname"]        nome
     *      $result["sn"]               cognome
     *      $result["telephonenumber"]  telefono
     *      $result["co"]
     *      $result["postalcode"]
     *      $result["streetaddress"]
     *      $result["st"]
     *      $result["l"]
     *      $result["info"]             codice fiscale
     *      $result["memberof"]         gruppi
     *      $result["postaladdress"]
     *      $result["token"]            token
     *      $result["timestamp"]        timestamp token        
     */
    function univpm_AD_login(string $username, string $password) {
        //send request to univpm ws
        $url = env('WSAUTH_URL') . '?username=' . $username . '&password=' . urlencode($password);
        $response = Http::withOptions(['verify' => false])->get($url)->json();
        
        if ($response["stato"]==1) {
            return $response;
        }
        return false;
    }
}