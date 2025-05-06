<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function __construct()
    {
    }
    
    /**
 * @OA\Get(
 *     path="/api/events",
 *     summary="Lister tous les événements de l'utilisateur connecté",
 *     tags={"Événements"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des événements récupérée avec succès",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Consultation médicale"),
 *                 @OA\Property(property="description", type="string", example="Suivi régulier"),
 *                 @OA\Property(property="start", type="string", format="date-time", example="2025-05-05 09:00:00"),
 *                 @OA\Property(property="end", type="string", format="date-time", example="2025-05-05 10:00:00"),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01 14:32:00"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-01 14:32:00")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non autorisé"
 *     )
 * )
 */

    public function index()
    {
        $event=Event::all();
        return response()->json(data: $event);

    }

        /**
     * @OA\Post(
     *     path="/api/events",
     *     summary="Créer un nouvel événement",
     *     tags={"Événements"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "start", "end"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="start", type="string", format="date-time"),
     *             @OA\Property(property="end", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Événement créé"),
     *     @OA\Response(response=422, description="Données invalides")
     * )
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'required|date_format:Y-m-d H:i:s|after:start',

        ]);

        // Ajoutez automatiquement l'ID de l'utilisateur connecté
            $event = $request->user()->events()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start' => Carbon::parse($validated['start'])->format('Y-m-d H:i:s'),
            'end' => Carbon::parse($validated['end'])->format('Y-m-d H:i:s'),
        ]);

        return response()->json($event, 201); 
    }

      /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     summary="Afficher le détail d’un événement",
     *     tags={"Événements"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Détail de l'événement"),
     *     @OA\Response(response=404, description="Non trouvé")
     * )
     */
    public function show(Request $request, $id)
    {
        $event = $request->user()->events()->findOrFail($id);
    
        return response()->json($event, 200);
    }

      /**
     * @OA\Patch(
     *     path="/api/events/{id}",
     *     summary="Modifier un événement",
     *     tags={"Événements"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'événement",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="start", type="string", format="date-time"),
     *             @OA\Property(property="end", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Événement mis à jour"),
     *     @OA\Response(response=404, description="Événement non trouvé")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|nullable|string',
            'start' => 'sometimes|date_format:Y-m-d H:i:s',
            'end' => 'sometimes|date_format:Y-m-d H:i:s|after:start',
        ]);
    
        $event = $request->user()->events()->findOrFail($id);
    
        if (isset($validated['start'])) {
            $validated['start'] = Carbon::parse($validated['start'])->format('Y-m-d H:i:s');
        }
        if (isset($validated['end'])) {
            $validated['end'] = Carbon::parse($validated['end'])->format('Y-m-d H:i:s');
        }
    
        $event->update($validated);
    
        return response()->json($event, 200);
    }
    
    
        /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     summary="Supprimer un événement",
     *     tags={"Événements"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Supprimé avec succès"),
     *     @OA\Response(response=404, description="Événement non trouvé")
     * )
     */

    public function destroy(Request $request, $id)
    {
        $event = $request->user()->events()->findOrFail($id);
    
        $event->delete();
    
        return response()->json(['message' => 'Événement supprimé avec succès.'], 200);
    }
}
