<?php

namespace App\Http\Controllers\TCCX;

use App\Http\Requests\TCCX\StoreQuestLocation;
use App\TCCX\Quest\QuestLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestLocationController extends Controller
{
    public function index()
    {
        $ql = QuestLocation::paginate(10);
        return view('tccx.quest.location.view', [
            'questLocations' => $ql
        ]);
    }

    /**
     * view create/edit page, Use for both create/edit
     */
    public function edit()
    {

    }

    /**
     * Use for both create/edit, create/edit post endpoint
     * @param StoreQuestLocation $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function editPost(StoreQuestLocation $request)
    {
        $creation = !QuestLocation::whereId($request->get('quest-location-id'))->exists();
        QuestLocation::updateOrCreate([
            ['id' => $request->get('quest-location-id')],
            [
                'name' => $request->get('name'),
                'type' => $request->get('type'),
                'lat' => $request->get('lat'),
                'lng' => $request->get('lng')
            ]
        ]);
        $status = [
            'success' => true,
            'message' => $creation ? 'Location has been created!' : 'Location has been updated!'
        ];
        return $request->ajax() ?
            response()->json($status) : redirect()->route('tccx.quest.locations')->with('status', $status);
    }

    public function delete(Request $request)
    {
        // validate
        $this->validate($request, [
            'quest-location-id' => 'required|exists:quest_locations,id'
        ]);
        // find model
        $ql = QuestLocation::whereId($request->get('quest-location-id'))->firstOrFail();
        // and delete it
        $ql->delete();
        $status = [
            'success' => true,
            'message' => 'Location has been deleted!'
        ];
        return $request->ajax() ?
            response()->json($status) :
            redirect()->route('tccx.quest.locations')->with('status', $status);
    }
}
