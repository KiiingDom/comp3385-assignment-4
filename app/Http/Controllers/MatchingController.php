<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PairingHistory;

class MatchingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $matches = $this->findMatches($user);
        return view('matching.index', compact('matches'));
    }

    protected function findMatches(User $user)
    {
        $users = User::where('id', '!=', $user->id)
            ->whereNotIn('id', PairingHistory::where('student_id', $user->id)->pluck('partner_id'))
            ->get();

        $matches = $users->map(function ($potentialMatch) use ($user) {
            $similarity = $this->calculateTagSimilarity($user, $potentialMatch);
            $potentialMatch->similarity = $similarity;
            return $potentialMatch;
        });

        return $matches->sortByDesc('similarity');
    }

    protected function calculateTagSimilarity(User $user1, User $user2)
    {
        // Extract tags from user profiles
        $tags1 = array_unique(explode(',', strtolower($user1->study_preferences . ',' . $user1->interests)));
        $tags2 = array_unique(explode(',', strtolower($user2->study_preferences . ',' . $user2->interests)));

        // Calculate common tags
        $commonTags = array_intersect($tags1, $tags2);

        // Calculate total unique tags
        $totalTags = array_unique(array_merge($tags1, $tags2));

        // Calculate similarity based on common tags
        $similarity = count($commonTags) / count($totalTags) * 100;

        return $similarity;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $partnerId = $request->input('partner_id');

        PairingHistory::create([
            'student_id' => $user->id,
            'partner_id' => $partnerId,
        ]);

        return redirect()->route('matching.index')->with('success', 'Matched successfully.');
    }
}