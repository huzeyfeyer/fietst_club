<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewsPolicy
{
    /**
     * Bepaal of de gebruiker modellen mag bekijken.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Iedereen mag de nieuwsindex zien
    }

    /**
     * Bepaal of de gebruiker het model mag bekijken.
     */
    public function view(?User $user, News $news): bool
    {
        return true; // Iedereen mag een specifiek nieuwsitem zien
    }

    /**
     * Bepaal of de gebruiker modellen mag aanmaken.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin(); // Alleen admins mogen nieuws aanmaken
    }

    /**
     * Bepaal of de gebruiker het model mag bijwerken.
     */
    public function update(User $user, News $news): bool
    {
        return $user->isAdmin(); // Alleen admins mogen nieuws bijwerken
    }

    /**
     * Bepaal of de gebruiker het model mag verwijderen.
     */
    public function delete(User $user, News $news): bool
    {
        return $user->isAdmin(); // Alleen admins mogen nieuws verwijderen
    }

    /**
     * Bepaal of de gebruiker het model mag herstellen.
     */
    public function restore(User $user, News $news): bool
    {
        return $user->isAdmin(); // Optioneel: alleen admins mogen herstellen (als soft deletes gebruikt worden)
    }

    /**
     * Bepaal of de gebruiker het model permanent mag verwijderen.
     */
    public function forceDelete(User $user, News $news): bool
    {
        return $user->isAdmin(); // Optioneel: alleen admins mogen permanent verwijderen (als soft deletes gebruikt worden)
    }
}
