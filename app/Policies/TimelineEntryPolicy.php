<?php

namespace App\Policies;

use App\Models\TimelineEntry;
use App\Models\User;

class TimelineEntryPolicy
{
    public function view(User $user, TimelineEntry $entry): bool
    {
        return $user->id === $entry->user_id;
    }

    public function update(User $user, TimelineEntry $entry): bool
    {
        return $user->id === $entry->user_id;
    }

    public function delete(User $user, TimelineEntry $entry): bool
    {
        return $user->id === $entry->user_id;
    }
}
