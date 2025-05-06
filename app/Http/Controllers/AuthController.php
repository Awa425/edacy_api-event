<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:4',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ], 201);
    }

    /**
 * @OA\Post(
 *      path="/api/login",
 *      operationId="login",
 *      tags={"login"},
 *      summary="Se connecter",
 *      description="Permet de se connecter avec un email, et un mot de passe.", 
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              type="object",
 *              required={"email", "password"},
 *              @OA\Property(
 *                  property="email",
 *                  type="string",
 *                  example="awa@gmail.com",
 *                  description="Email de l'utilisateur"
 *              ),
 *              @OA\Property(
 *                  property="password",
 *                  type="string",
 *                  example="passer"
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Connexion réussie",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="token", type="string"),
 *              @OA\Property(property="data", type="object")
 *          )
 *      ),
 *      @OA\Response( 
 *          response=401,
 *          description="Identifiants incorrects"
 *      ),
 *      @OA\Response( 
 *          response=422,
 *          description="Erreur de validation"
 *      )
 * )
 */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email ou mot de pass incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }

    public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out']);
        }
}
