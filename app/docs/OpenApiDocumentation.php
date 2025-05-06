<?php

namespace App\Docs;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Gestion Medical",
 *      description="Documentation API pour la  gestion Medical",
 *      @OA\Contact(
 *          email="diopawa425@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * ),
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Serveur API"
 * ),
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="sanctumAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",  
 *         description="Entrez le token Sanctum reçu après connexion."
 *     )
 * )
 */
class OpenApiDocumentation{}
