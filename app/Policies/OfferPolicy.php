<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OfferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // ograć, jak będą role
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Offer $offer): Response
    {
        return $user->id == $offer->user_id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        // ograć jak będa ograniczenia
        return $user->hasVerifiedEmail()
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Offer $offer): Response
    {
        return $user->id == $offer->user_id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Offer $offer): Response
    {
        return $user->id == $offer->user_id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }
}
